<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Services\AmortizacionService;
use App\Services\PagoService;
use App\Support\Estado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrestamoController extends Controller
{
    public function index(PagoService $pagoService)
    {
        $pagoService->marcarCuotasVencidas();

        $prestamos = Prestamo::where('user_id', Auth::id())
            ->with(['cuotas', 'solicitud'])
            ->latest()
            ->get();

        $prestamos->each(fn ($prestamo) => $pagoService->actualizarEstadoPrestamo($prestamo));
        $prestamos = Prestamo::where('user_id', Auth::id())
            ->with('solicitud')
            ->latest()
            ->get();

        return view('cliente.mis-prestamos', compact('prestamos'));
    }

    public function estadoCuentaGeneral(PagoService $pagoService)
    {
        $pagoService->marcarCuotasVencidas();

        $prestamosResumen = Prestamo::where('user_id', Auth::id())
            ->with(['solicitud', 'cuotas.prestamo', 'pagos.prestamo'])
            ->latest()
            ->get();

        $prestamosResumen->each(fn ($prestamo) => $pagoService->actualizarEstadoPrestamo($prestamo));

        $prestamosResumen = Prestamo::where('user_id', Auth::id())
            ->with(['solicitud', 'cuotas.prestamo', 'pagos.prestamo'])
            ->latest()
            ->get();

        $cuotas = $prestamosResumen->flatMap(fn ($prestamo) => $prestamo->cuotas);
        $pagos = $prestamosResumen->flatMap(fn ($prestamo) => $prestamo->pagos);

        $capitalPrestado = $prestamosResumen->sum(fn ($prestamo) => (float) ($prestamo->monto_original ?? $prestamo->solicitud?->monto_solicitado ?? $prestamo->monto_total));
        $totalAPagar = $prestamosResumen->sum(fn ($prestamo) => (float) $prestamo->monto_total);
        $interesGenerado = $cuotas->isNotEmpty()
            ? $cuotas->sum(fn ($cuota) => (float) $cuota->interes)
            : max(0, $totalAPagar - $capitalPrestado);
        $saldoPendiente = $prestamosResumen->sum(fn ($prestamo) => (float) $prestamo->saldo_pendiente);
        $moraPendiente = $cuotas->sum(fn ($cuota) => $cuota->calcularInteresMoratorio());

        $resumen = [
            'prestamos_total' => $prestamosResumen->count(),
            'prestamos_activos' => $prestamosResumen->where('estado', Estado::ACTIVO)->count(),
            'prestamos_liquidados' => $prestamosResumen->where('estado', Estado::LIQUIDADO)->count(),
            'prestamos_en_mora' => $prestamosResumen->where('estado', Estado::EN_MORA)->count(),
            'capital_prestado' => $capitalPrestado,
            'interes_generado' => $interesGenerado,
            'mora_pendiente' => $moraPendiente,
            'mora_pagada' => $cuotas->sum(fn ($cuota) => (float) $cuota->mora_pagada),
            'total_pagado' => $pagos->sum(fn ($pago) => (float) $pago->monto),
            'capital_pagado' => $pagos->sum(fn ($pago) => (float) $pago->capital_pagado),
            'interes_pagado' => $pagos->sum(fn ($pago) => (float) $pago->interes_ordinario_pagado),
            'saldo_pendiente' => $saldoPendiente,
            'saldo_con_mora' => $saldoPendiente + $moraPendiente,
            'cuotas_vencidas' => $cuotas->where('estado', Estado::VENCIDA)->count(),
            'cuotas_pendientes' => $cuotas->whereIn('estado', [Estado::PENDIENTE, Estado::PARCIALMENTE_PAGADA, Estado::VENCIDA])->count(),
        ];

        $proximaCuota = $cuotas
            ->where('estado', '!=', Estado::PAGADA)
            ->sortBy('fecha_vencimiento')
            ->first();

        $prestamos = Prestamo::where('user_id', Auth::id())
            ->with(['solicitud', 'cuotas'])
            ->latest()
            ->paginate(6, ['*'], 'prestamos_page')
            ->withQueryString();

        $pagosRecientes = Pago::where('user_id', Auth::id())
            ->with('prestamo')
            ->latest('fecha_pago')
            ->paginate(8, ['*'], 'pagos_page')
            ->withQueryString();

        return view('cliente.estado-cuenta-general', compact('prestamos', 'resumen', 'proximaCuota', 'pagosRecientes'));
    }

    public function estadoCuenta($id, AmortizacionService $amortizacion, PagoService $pagoService)
    {
        $pagoService->marcarCuotasVencidas();

        $prestamo = Prestamo::where('user_id', Auth::id())
            ->with(['solicitud', 'pagos', 'cuotas'])
            ->findOrFail($id);

        if ($prestamo->cuotas->isEmpty()) {
            DB::transaction(function () use ($prestamo, $amortizacion) {
                $amortizacion->guardarTabla($prestamo);
            });

            $prestamo->load(['solicitud', 'pagos', 'cuotas']);
        }

        $pagoService->marcarCuotasVencidas();
        $prestamo->refresh()->load(['solicitud', 'pagos', 'cuotas']);
        $pagoService->actualizarEstadoPrestamo($prestamo);
        $prestamo->refresh()->load(['solicitud', 'pagos', 'cuotas']);

        $pagos = $prestamo->pagos()
            ->latest('fecha_pago')
            ->paginate(8, ['*'], 'pagos_page')
            ->withQueryString();

        $cuotas = $prestamo->cuotas()
            ->orderBy('numero')
            ->paginate(8, ['*'], 'cuotas_page')
            ->withQueryString();

        return view('cliente.estado-cuenta', compact('prestamo', 'pagos', 'cuotas'));
    }
}
