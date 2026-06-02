<?php
namespace App\Services;

use App\Models\Prestamo;
use App\Models\Pago;
use App\Models\Cuota;
use App\Support\Estado;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PagoService
{
    /**
     * Registra un pago con cascada:
     * 1) Interés moratorio
     * 2) Interés ordinario
     * 3) Capital
     */
    public function registrarPago(Prestamo $prestamo, float $monto, Carbon $fechaPago, string $notas = ''): Pago
    {
        return DB::transaction(function () use ($prestamo, $monto, $fechaPago, $notas) {

            $saldoDisponible = $monto;
            $totalMoratorio  = 0;
            $totalOrdinario  = 0;
            $totalCapital    = 0;
            $cuotasAfectadas = [];

            // Obtener cuotas solicitadas ordenadas por número
            $cuotas = $prestamo->cuotas()
                ->whereIn('estado', [Estado::PENDIENTE, Estado::VENCIDA, Estado::PARCIALMENTE_PAGADA])
                ->orderBy('numero')
                ->get();

            foreach ($cuotas as $cuota) {
                if ($saldoDisponible <= 0) break;

                // 1. Interés moratorio si la cuota está en mora
                $moratorio = $cuota->calcularInteresmoratorio();
                if ($moratorio > 0 && $saldoDisponible > 0) {
                    $pagarMoratorio = min($moratorio, $saldoDisponible);
                    $saldoDisponible -= $pagarMoratorio;
                    $totalMoratorio  += $pagarMoratorio;
                }

                // 2. Interés ordinario pendiente
                $interesOrdinario = max(0, $cuota->interes - ($cuota->monto_pagado > 0 ? min($cuota->monto_pagado, $cuota->interes) : 0));
                if ($interesOrdinario > 0 && $saldoDisponible > 0) {
                    $pagarInteres = min($interesOrdinario, $saldoDisponible);
                    $saldoDisponible -= $pagarInteres;
                    $totalOrdinario  += $pagarInteres;
                }

                // 3. Capital
                $saldoPendiente = $cuota->saldo_pendiente;
                if ($saldoPendiente > 0 && $saldoDisponible > 0) {
                    $pagarCapital    = min($saldoPendiente, $saldoDisponible);
                    $saldoDisponible -= $pagarCapital;
                    $totalCapital    += $pagarCapital;

                    $montoAplicado = $pagarCapital;
                    $cuota->monto_pagado = round($cuota->monto_pagado + $montoAplicado, 2);

                    // Determinar nuevo estado de la cuota
                    if ($cuota->monto_pagado >= $cuota->cuota_total) {
                        $cuota->estado = Estado::PAGADA;
                    } else {
                        $cuota->estado = Estado::PARCIALMENTE_PAGADA;
                    }

                    $cuota->save();
                    $cuotasAfectadas[] = ['cuota' => $cuota, 'monto' => $montoAplicado];
                }
            }

            // Crear registro de pago
            $pago = Pago::create([
                'prestamo_id'              => $prestamo->id,
                'fecha_pago'               => $fechaPago,
                'monto'                    => $monto,
                'interes_moratorio_pagado' => $totalMoratorio,
                'interes_ordinario_pagado' => $totalOrdinario,
                'capital_pagado'           => $totalCapital,
                'notas'                    => $notas,
            ]);

            // Asociar pago con cuotas (muchos a muchos)
            foreach ($cuotasAfectadas as $item) {
                $pago->cuotas()->attach($item['cuota']->id, [
                    'monto_aplicado' => $item['monto'],
                ]);
            }

            // Actualizar estado del préstamo
            $this->actualizarEstadoPrestamo($prestamo);

            return $pago;
        });
    }

    /**
     * Actualiza el estado del préstamo según sus cuotas.
     */
    public function actualizarEstadoPrestamo(Prestamo $prestamo): void
    {
        $prestamo->refresh();
        $cuotas = $prestamo->cuotas;
        $saldoPendiente = round($cuotas->sum(fn ($cuota) => $cuota->saldo_pendiente), 2);

        $todasPagadas = $cuotas->isNotEmpty()
            && $cuotas->every(fn($c) => $c->estado === Estado::PAGADA);

        if ($todasPagadas) {
            $prestamo->update([
                'saldo_pendiente' => 0,
                'estado' => Estado::LIQUIDADO,
            ]);
            return;
        }

        $tieneVencidas = $cuotas->contains(function ($c) {
            return in_array($c->estado, [Estado::VENCIDA, Estado::PARCIALMENTE_PAGADA])
                && $c->fecha_vencimiento->isPast();
        });

        $prestamo->update([
            'saldo_pendiente' => $saldoPendiente,
            'estado' => $tieneVencidas ? Estado::EN_MORA : Estado::ACTIVO,
        ]);
    }

    /**
     * Marca cuotas en mora sin liquidar (ejecutar con scheduler).
     */
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
}
