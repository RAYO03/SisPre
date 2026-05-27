<?php
namespace App\Services;

use App\Models\Prestamo;
use App\Models\Cuota;
use Carbon\Carbon;

class AmortizacionService
{
    /**
     * Calcula la cuota fija usando sistema francés.
     * cuota = P * i / (1 - (1+i)^-n)
     */
    public function calcularCuotaFija(float $capital, float $tasaAnual, int $plazoMeses): float
    {
        $i = ($tasaAnual / 100) / 12; // tasa mensual
        if ($i == 0) return round($capital / $plazoMeses, 2);
        $cuota = $capital * $i / (1 - pow(1 + $i, -$plazoMeses));
        return round($cuota, 2);
    }

    /**
     * Genera la tabla de amortización completa.
     * Devuelve array de cuotas sin guardar en BD.
     */
    public function generarTabla(float $capital, float $tasaAnual, int $plazoMeses, Carbon $fechaInicio): array
    {
        $i = ($tasaAnual / 100) / 12;
        $cuotaFija = $this->calcularCuotaFija($capital, $tasaAnual, $plazoMeses);
        $saldo = $capital;
        $tabla = [];

        for ($n = 1; $n <= $plazoMeses; $n++) {
            $interes = round($saldo * $i, 2);
            $capitalAbono = round($cuotaFija - $interes, 2);

            // Ajuste en la última cuota para que el saldo quede en exactamente 0
            if ($n === $plazoMeses) {
                $capitalAbono = $saldo;
                $cuotaReal = round($capitalAbono + $interes, 2);
            } else {
                $cuotaReal = $cuotaFija;
            }

            $saldo = round($saldo - $capitalAbono, 2);

            $tabla[] = [
                'numero'           => $n,
                'fecha_vencimiento'=> (clone $fechaInicio)->addMonths($n)->toDateString(),
                'capital'          => $capitalAbono,
                'interes'          => $interes,
                'cuota_total'      => $cuotaReal,
                'saldo_restante'   => $saldo,
                'monto_pagado'     => 0,
                'estado'           => 'pendiente',
            ];
        }

        return $tabla;
    }

    /**
     * Persiste la tabla en BD asociada al préstamo.
     */
    public function guardarTabla(Prestamo $prestamo): void
    {
        $tabla = $this->generarTabla(
            (float) $prestamo->capital,
            (float) $prestamo->tasa_anual,
            $prestamo->plazo_meses,
            $prestamo->fecha_inicio
        );

        foreach ($tabla as $fila) {
            $prestamo->cuotas()->create($fila);
        }
    }
}