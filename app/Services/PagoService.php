<?php
namespace App\Services;

use App\Models\Prestamo;
use App\Models\Pago;
use App\Models\Cuota;
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

            // Obtener cuotas pendientes ordenadas por número
            $cuotas = $prestamo->cuotas()
                ->whereIn('estado', ['pendiente', 'vencida', 'parcialmente_pagada'])
                ->orderBy('numero')
                ->get();

            foreach ($cuotas as $cuota) {
                if ($saldoDisponible <= 0) break;

                // 1. Interés moratorio si la cuota está vencida
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
                        $cuota->estado = 'pagada';
                    } else {
                        $cuota->estado = 'parcialmente_pagada';
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

        $todasPagadas = $cuotas->every(fn($c) => $c->estado === 'pagada');

        if ($todasPagadas) {
            $prestamo->update(['estado' => 'liquidado']);
            return;
        }

        $tieneVencidas = $cuotas->contains(function ($c) {
            return in_array($c->estado, ['vencida', 'parcialmente_pagada'])
                && $c->fecha_vencimiento->isPast();
        });

        if ($tieneVencidas && $prestamo->estado === 'activo') {
            $prestamo->update(['estado' => 'en_mora']);
        } elseif (!$tieneVencidas && $prestamo->estado === 'en_mora') {
            $prestamo->update(['estado' => 'activo']);
        }
    }

    /**
     * Marca cuotas vencidas sin pagar (ejecutar con scheduler).
     */
    public function marcarCuotasVencidas(): void
    {
        Cuota::where('estado', 'pendiente')
            ->where('fecha_vencimiento', '<', now())
            ->update(['estado' => 'vencida']);
    }
}