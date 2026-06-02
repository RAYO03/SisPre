<?php

namespace App\Services;

use App\Models\Cuota;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Support\Estado;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PagoService
{
    public function registrarPago(
        Prestamo $prestamo,
        float $monto,
        Carbon $fechaPago,
        string $metodoPago,
        int $userId,
        ?string $comprobante = null
    ): Pago {
        return DB::transaction(function () use ($prestamo, $monto, $fechaPago, $metodoPago, $userId, $comprobante) {
            $prestamo->loadMissing('cuotas');

            $desglose = $this->aplicarPagoACuotas($prestamo, $monto, $fechaPago);

            $pago = Pago::create([
                'prestamo_id' => $prestamo->id,
                'user_id' => $userId,
                'folio_pago' => 'PG-' . date('Y') . '-' . str_pad((string) (Pago::count() + 1), 6, '0', STR_PAD_LEFT),
                'monto' => round($monto, 2),
                'interes_moratorio_pagado' => $desglose['interes_moratorio'],
                'interes_ordinario_pagado' => $desglose['interes_ordinario'],
                'capital_pagado' => $desglose['capital'],
                'metodo_pago' => $metodoPago,
                'fecha_pago' => $fechaPago->toDateString(),
                'comprobante' => $comprobante,
                'estado' => Estado::LIQUIDADO,
            ]);

            foreach ($desglose['cuotas'] as $cuotaAplicada) {
                $pago->cuotas()->attach($cuotaAplicada['cuota_id'], [
                    'monto_aplicado' => $cuotaAplicada['monto_aplicado'],
                ]);
            }

            $this->actualizarEstadoPrestamo($prestamo);

            return $pago;
        });
    }

    public function siguienteCuotaParaPago(Prestamo $prestamo): ?Cuota
    {
        return $prestamo->cuotas
            ->whereIn('estado', [Estado::PARCIALMENTE_PAGADA, Estado::VENCIDA, Estado::PENDIENTE])
            ->sortBy('numero')
            ->first();
    }

    public function resumenSiguientePago(Prestamo $prestamo, ?Cuota $cuota, ?Carbon $fechaReferencia = null): array
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

        $fechaCalculo = $fechaReferencia ?? now();
        $mora = $cuota->calcularInteresMoratorio(fechaReferencia: $fechaCalculo);
        $saldoCuota = $cuota->saldo_pendiente;
        $diasAtraso = max(0, (int) floor($cuota->fecha_vencimiento->copy()->startOfDay()->diffInDays($fechaCalculo->copy()->startOfDay(), false)));

        return [
            'numero' => $cuota->numero,
            'cuota_total' => (float) $cuota->cuota_total,
            'saldo_cuota' => $saldoCuota,
            'dias_atraso' => $diasAtraso,
            'mora' => $mora,
            'mora_pagada' => (float) $cuota->mora_pagada,
            'total_sugerido' => round($saldoCuota + $mora, 2),
            'esta_vencida' => $diasAtraso > 0,
        ];
    }

    public function montoTotalPendienteParaPago(Prestamo $prestamo, ?Carbon $fechaReferencia = null): float
    {
        return round($prestamo->cuotas
            ->whereIn('estado', [Estado::PARCIALMENTE_PAGADA, Estado::VENCIDA, Estado::PENDIENTE])
            ->sum(fn (Cuota $cuota) => $cuota->saldo_pendiente + $cuota->calcularInteresMoratorio(fechaReferencia: $fechaReferencia)), 2);
    }

    public function moraPendienteParaPago(Prestamo $prestamo, ?Carbon $fechaReferencia = null): float
    {
        return round($prestamo->cuotas
            ->whereIn('estado', [Estado::PARCIALMENTE_PAGADA, Estado::VENCIDA, Estado::PENDIENTE])
            ->sum(fn (Cuota $cuota) => $cuota->calcularInteresMoratorio(fechaReferencia: $fechaReferencia)), 2);
    }

    public function cuotasPendientesParaResumen(Prestamo $prestamo, int $limite = 5, ?Carbon $fechaReferencia = null)
    {
        return $prestamo->cuotas
            ->whereIn('estado', [Estado::PARCIALMENTE_PAGADA, Estado::VENCIDA, Estado::PENDIENTE])
            ->sortBy('numero')
            ->take($limite)
            ->map(function (Cuota $cuota) use ($fechaReferencia) {
                $mora = $cuota->calcularInteresMoratorio(fechaReferencia: $fechaReferencia);

                return [
                    'numero' => $cuota->numero,
                    'fecha_vencimiento' => $cuota->fecha_vencimiento,
                    'saldo_cuota' => $cuota->saldo_pendiente,
                    'mora' => $mora,
                    'por_cubrir' => round($cuota->saldo_pendiente + $mora, 2),
                    'estado' => $cuota->estado_label,
                ];
            });
    }

    public function actualizarEstadoPrestamo(Prestamo $prestamo): void
    {
        $prestamo->refresh();
        $cuotas = $prestamo->cuotas;
        $saldoPendiente = round($cuotas->sum(fn ($cuota) => $cuota->saldo_pendiente), 2);

        $todasPagadas = $cuotas->isNotEmpty()
            && $cuotas->every(fn ($c) => $c->estado === Estado::PAGADA);

        if ($todasPagadas) {
            $prestamo->update([
                'saldo_pendiente' => 0,
                'estado' => Estado::LIQUIDADO,
            ]);

            return;
        }

        $tieneVencidas = $cuotas->contains(function ($c) {
            return in_array($c->estado, [Estado::VENCIDA, Estado::PARCIALMENTE_PAGADA], true)
                && $c->fecha_vencimiento->isPast();
        });

        $prestamo->update([
            'saldo_pendiente' => $saldoPendiente,
            'estado' => $tieneVencidas ? Estado::EN_MORA : Estado::ACTIVO,
        ]);
    }

    public function marcarCuotasVencidas(): void
    {
        Cuota::whereIn('estado', [Estado::PENDIENTE, Estado::PARCIALMENTE_PAGADA])
            ->where('fecha_vencimiento', '<', now())
            ->update(['estado' => Estado::VENCIDA]);

        Prestamo::where('estado', Estado::ACTIVO)
            ->whereHas('cuotas', function ($query) {
                $query->where('estado', Estado::VENCIDA);
            })
            ->update(['estado' => Estado::EN_MORA]);

        Prestamo::where('estado', Estado::EN_MORA)
            ->whereDoesntHave('cuotas', function ($query) {
                $query->where('estado', Estado::VENCIDA);
            })
            ->whereHas('cuotas')
            ->update(['estado' => Estado::ACTIVO]);
    }

    private function aplicarPagoACuotas(Prestamo $prestamo, float $monto, Carbon $fechaPago): array
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
            $moratorio = $cuota->calcularInteresMoratorio(fechaReferencia: $fechaPago);

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
}
