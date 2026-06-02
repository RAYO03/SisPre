<?php
namespace App\Services;

use App\Support\Estado;

use App\Models\Prestamo;
use App\Models\Cuota;
use Carbon\Carbon;

class AmortizacionService
{
    public const TASAS_ANUALES_POR_PLAZO = [
        3 => 18.00,
        6 => 19.00,
        12 => 19.90,
        18 => 20.50,
        24 => 22.00,
    ];

    public function plazosPermitidos(): array
    {
        return array_keys(self::TASAS_ANUALES_POR_PLAZO);
    }

    public function tasaAnualPorPlazo(int $plazoMeses): float
    {
        return self::TASAS_ANUALES_POR_PLAZO[$plazoMeses] ?? self::TASAS_ANUALES_POR_PLAZO[12];
    }

    public function generarResumen(float $capital, int $plazoMeses, ?Carbon $fechaInicio = null): array
    {
        $tasaAnual = $this->tasaAnualPorPlazo($plazoMeses);
        $tabla = $this->generarTabla($capital, $tasaAnual, $plazoMeses, $fechaInicio ?? now());

        return [
            'tasa_anual' => $tasaAnual,
            'pago_mensual' => $this->calcularCuotaFija($capital, $tasaAnual, $plazoMeses),
            'total_pagar' => round(array_sum(array_column($tabla, 'cuota_total')), 2),
            'tabla' => $tabla,
        ];
    }

    /**
     * Calcula la cuota fija usando sistema francés.
     * cuota = P * i / (1 - (1+i)^-n)
     */
    public function calcularCuotaFija(float $capital, float $tasaAnual, int $plazoMeses): float
    {
        if ($capital <= 0 || $tasaAnual < 0 || $plazoMeses <= 0) {
            return 0;
        }

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
        if ($capital <= 0 || $tasaAnual < 0 || $plazoMeses <= 0) {
            return [];
        }

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

            $saldo = max(0, round($saldo - $capitalAbono, 2));

            $tabla[] = [
                'numero'           => $n,
                'fecha_vencimiento'=> (clone $fechaInicio)->addMonthsNoOverflow($n)->toDateString(),
                'capital'          => $capitalAbono,
                'interes'          => $interes,
                'cuota_total'      => $cuotaReal,
                'saldo_restante'   => $saldo,
                'monto_pagado'     => 0,
                'estado'           => Estado::PENDIENTE,
            ];
        }

        return $tabla;
    }

    /**
     * Persiste la tabla en BD asociada al préstamo.
     */
    public function guardarTabla(Prestamo $prestamo): void
    {
        $capital = $prestamo->monto_original
            ?? $prestamo->solicitud?->monto_solicitado
            ?? $prestamo->monto_total;

        $tabla = $this->generarTabla(
            (float) $capital,
            (float) $prestamo->tasa_interes,
            $prestamo->plazo_meses,
            Carbon::parse($prestamo->fecha_inicio)
        );

        foreach ($tabla as $fila) {
            $prestamo->cuotas()->create($fila);
        }
    }
}
