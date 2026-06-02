<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Services\AmortizacionService;
use App\Services\PagoService;
use App\Support\Estado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::where('user_id', Auth::id())
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $prestamosActivos = Prestamo::where('user_id', Auth::id())
            ->whereIn('estado', [Estado::ACTIVO, Estado::EN_MORA])
            ->latest()
            ->get();

        return view('cliente.pagos', compact('pagos', 'prestamosActivos'));
    }

    public function create(Request $request, $prestamo_id, AmortizacionService $amortizacion, PagoService $pagoService)
    {
        $pagoService->marcarCuotasVencidas();

        $prestamo = Prestamo::where('user_id', Auth::id())
            ->with('cuotas')
            ->findOrFail($prestamo_id);

        if ($prestamo->cuotas->isEmpty()) {
            $amortizacion->guardarTabla($prestamo);
            $prestamo->load('cuotas');
        }

        $pagoService->actualizarEstadoPrestamo($prestamo);
        $prestamo->refresh()->load('cuotas');

        if ($prestamo->estado === Estado::LIQUIDADO) {
            return redirect()
                ->route('cliente.estado-cuenta', $prestamo->id)
                ->with('success', 'Este prestamo ya esta liquidado.');
        }

        $returnTo = $request->query('return_to');
        $siguienteCuota = $this->siguienteCuotaParaPago($prestamo);
        $resumenPago = $this->resumenSiguientePago($prestamo, $siguienteCuota);
        $montoSugerido = $resumenPago['total_sugerido'];
        $montoMaximo = $this->montoTotalPendienteParaPago($prestamo);

        return view('cliente.realizar-pago', compact('prestamo', 'returnTo', 'montoSugerido', 'montoMaximo', 'siguienteCuota', 'resumenPago'));
    }

    public function store(Request $request, $prestamo_id, AmortizacionService $amortizacion, PagoService $pagoService)
    {
        $pagoService->marcarCuotasVencidas();

        $prestamo = Prestamo::where('user_id', Auth::id())
            ->with('cuotas')
            ->findOrFail($prestamo_id);

        $pagoService->actualizarEstadoPrestamo($prestamo);
        $prestamo->refresh()->load('cuotas');

        if ($prestamo->estado === Estado::LIQUIDADO) {
            return redirect()
                ->route('cliente.estado-cuenta', $prestamo->id)
                ->with('success', 'Este prestamo ya esta liquidado.');
        }

        $montoMaximo = $this->montoTotalPendienteParaPago($prestamo);

        $request->validate([
            'monto' => ['required', 'numeric', 'min:0.01', 'max:' . $montoMaximo],
            'metodo_pago' => 'required|in:Transferencia,Deposito,Efectivo,Tarjeta',
            'fecha_pago' => 'required|date|before_or_equal:today',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'return_to' => 'nullable|in:estado-cuenta,pagos',
        ]);

        DB::transaction(function () use ($request, $prestamo, $amortizacion, $pagoService) {
            if ($prestamo->cuotas->isEmpty()) {
                $amortizacion->guardarTabla($prestamo);
                $prestamo->load('cuotas');
            }

            $comprobante = $request->file('comprobante')
                ? $request->file('comprobante')->store('comprobantes', 'public')
                : null;

            $desglose = $this->aplicarPagoACuotas($prestamo, (float) $request->monto);

            $pago = Pago::create([
                'prestamo_id' => $prestamo->id,
                'user_id' => Auth::id(),
                'folio_pago' => 'PG-' . date('Y') . '-' . str_pad(Pago::count() + 1, 6, '0', STR_PAD_LEFT),
                'monto' => $request->monto,
                'interes_moratorio_pagado' => $desglose['interes_moratorio'],
                'interes_ordinario_pagado' => $desglose['interes_ordinario'],
                'capital_pagado' => $desglose['capital'],
                'metodo_pago' => $request->metodo_pago,
                'fecha_pago' => $request->fecha_pago,
                'comprobante' => $comprobante,
                'estado' => Estado::LIQUIDADO,
            ]);

            foreach ($desglose['cuotas'] as $cuotaAplicada) {
                $pago->cuotas()->attach($cuotaAplicada['cuota_id'], [
                    'monto_aplicado' => $cuotaAplicada['monto_aplicado'],
                ]);
            }

            $pagoService->actualizarEstadoPrestamo($prestamo);
        });

        if ($request->return_to === 'estado-cuenta') {
            return redirect()
                ->route('cliente.estado-cuenta', $prestamo->id)
                ->with('success', 'Pago registrado correctamente. La tabla de amortizacion fue actualizada.');
        }

        return redirect()
            ->route('cliente.pagos')
            ->with('success', 'Pago registrado correctamente.');
    }

    private function aplicarPagoACuotas(Prestamo $prestamo, float $monto): array
    {
        $saldoDisponible = round($monto, 2);
        $totalMoratorio = 0;
        $totalOrdinario = 0;
        $totalCapital = 0;
        $cuotasAplicadas = [];

        $cuotas = $prestamo->cuotas()
            ->whereIn('estado', [Estado::PENDIENTE, Estado::VENCIDA, Estado::PARCIALMENTE_PAGADA])
            ->orderBy('numero')
            ->lockForUpdate()
            ->get();

        foreach ($cuotas as $cuota) {
            if ($saldoDisponible <= 0) {
                break;
            }

            $montoAplicadoCuota = 0;
            $montoAplicadoAmortizacion = 0;
            $moratorio = $cuota->calcularInteresMoratorio();

            if ($moratorio > 0 && $saldoDisponible > 0) {
                $pagoMoratorio = min($moratorio, $saldoDisponible);
                $saldoDisponible = round($saldoDisponible - $pagoMoratorio, 2);
                $totalMoratorio = round($totalMoratorio + $pagoMoratorio, 2);
                $montoAplicadoCuota = round($montoAplicadoCuota + $pagoMoratorio, 2);
                $cuota->mora_pagada = round((float) $cuota->mora_pagada + $pagoMoratorio, 2);
            }

            $interesPagadoPreviamente = min((float) $cuota->monto_pagado, (float) $cuota->interes);
            $interesPendiente = max(0, round((float) $cuota->interes - $interesPagadoPreviamente, 2));

            if ($interesPendiente > 0 && $saldoDisponible > 0) {
                $pagoInteres = min($interesPendiente, $saldoDisponible);
                $saldoDisponible = round($saldoDisponible - $pagoInteres, 2);
                $totalOrdinario = round($totalOrdinario + $pagoInteres, 2);
                $montoAplicadoCuota = round($montoAplicadoCuota + $pagoInteres, 2);
                $montoAplicadoAmortizacion = round($montoAplicadoAmortizacion + $pagoInteres, 2);
            }

            $saldoCuotaSinMora = max(0, round((float) $cuota->cuota_total - (float) $cuota->monto_pagado - $montoAplicadoAmortizacion, 2));

            if ($saldoCuotaSinMora > 0 && $saldoDisponible > 0) {
                $pagoCapital = min($saldoCuotaSinMora, $saldoDisponible);
                $saldoDisponible = round($saldoDisponible - $pagoCapital, 2);
                $totalCapital = round($totalCapital + $pagoCapital, 2);
                $montoAplicadoCuota = round($montoAplicadoCuota + $pagoCapital, 2);
                $montoAplicadoAmortizacion = round($montoAplicadoAmortizacion + $pagoCapital, 2);
            }

            $montoAplicadoAmortizacion = min(
                $montoAplicadoAmortizacion,
                max(0, round((float) $cuota->cuota_total - (float) $cuota->monto_pagado, 2))
            );

            if ($montoAplicadoAmortizacion > 0) {
                $cuota->monto_pagado = round((float) $cuota->monto_pagado + $montoAplicadoAmortizacion, 2);
                $cuota->estado = $cuota->monto_pagado >= (float) $cuota->cuota_total
                    ? Estado::PAGADA
                    : Estado::PARCIALMENTE_PAGADA;
            }

            if ($montoAplicadoCuota > 0) {
                $cuota->save();
            }

            if ($montoAplicadoCuota > 0) {
                $cuotasAplicadas[] = [
                    'cuota_id' => $cuota->id,
                    'monto_aplicado' => $montoAplicadoCuota,
                ];
            }
        }

        return [
            'interes_moratorio' => $totalMoratorio,
            'interes_ordinario' => $totalOrdinario,
            'capital' => $totalCapital,
            'cuotas' => $cuotasAplicadas,
        ];
    }

    private function montoSugeridoParaPago(Prestamo $prestamo): float
    {
        return $this->resumenSiguientePago($prestamo, $this->siguienteCuotaParaPago($prestamo))['total_sugerido'];
    }

    private function siguienteCuotaParaPago(Prestamo $prestamo)
    {
        return $prestamo->cuotas
            ->whereIn('estado', [Estado::PARCIALMENTE_PAGADA, Estado::VENCIDA, Estado::PENDIENTE])
            ->sortBy('numero')
            ->first();
    }

    private function resumenSiguientePago(Prestamo $prestamo, $cuota): array
    {
        if (! $cuota) {
            $saldo = round((float) $prestamo->saldo_pendiente, 2);

            return [
                'numero' => null,
                'cuota_total' => 0,
                'saldo_cuota' => $saldo,
                'dias_atraso' => 0,
                'mora' => 0,
                'mora_pagada' => 0,
                'total_sugerido' => $saldo,
                'esta_vencida' => false,
            ];
        }

        $mora = $cuota->calcularInteresMoratorio();
        $saldoCuota = $cuota->saldo_pendiente;
        $totalSugerido = round(min((float) $prestamo->saldo_pendiente, $saldoCuota + $mora), 2);

        return [
            'numero' => $cuota->numero,
            'cuota_total' => (float) $cuota->cuota_total,
            'saldo_cuota' => $saldoCuota,
            'dias_atraso' => $cuota->dias_atraso,
            'mora' => $mora,
            'mora_pagada' => (float) $cuota->mora_pagada,
            'total_sugerido' => $totalSugerido,
            'esta_vencida' => $cuota->dias_atraso > 0,
        ];
    }

    private function montoTotalPendienteParaPago(Prestamo $prestamo): float
    {
        return round($prestamo->cuotas
            ->whereIn('estado', [Estado::PARCIALMENTE_PAGADA, Estado::VENCIDA, Estado::PENDIENTE])
            ->sum(fn ($cuota) => $cuota->saldo_pendiente + $cuota->calcularInteresMoratorio()), 2);
    }
}
