<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cliente;

class SimuladorPrestamo extends Component
{
    public $clientes;
    public $cliente_id;
    public $capital = 10000;
    public $tasa_anual = 24;
    public $plazo_meses = 12;
    public $fecha_inicio;
    public $frecuencia = 'mensual';

    public function mount()
    {
        $this->clientes = Cliente::orderBy('nombre')->get();
        $this->fecha_inicio = date('Y-m-d');
    }

    public function calcularCuota()
    {
        $capital = floatval($this->capital);
        $tasa = floatval($this->tasa_anual) / 100 / 12;
        $plazo = intval($this->plazo_meses);

        if ($capital <= 0 || $plazo <= 0) {
            return 0;
        }

        if ($tasa == 0) {
            return round($capital / $plazo, 2);
        }

        return round($capital * $tasa / (1 - pow(1 + $tasa, -$plazo)), 2);
    }

    public function tablaAmortizacion()
    {
        $capital = floatval($this->capital);
        $tasa = floatval($this->tasa_anual) / 100 / 12;
        $plazo = intval($this->plazo_meses);
        $cuota = $this->calcularCuota();
        $saldo = $capital;
        $tabla = [];

        if ($capital <= 0 || $plazo <= 0) {
            return [];
        }

        for ($i = 1; $i <= $plazo; $i++) {
            $interes = round($saldo * $tasa, 2);
            $abonoCapital = round($cuota - $interes, 2);

            if ($i == $plazo) {
                $abonoCapital = $saldo;
                $cuotaFinal = round($abonoCapital + $interes, 2);
            } else {
                $cuotaFinal = $cuota;
            }

            $saldo = round($saldo - $abonoCapital, 2);

            $tabla[] = [
                'numero' => $i,
                'cuota' => $cuotaFinal,
                'interes' => $interes,
                'capital' => $abonoCapital,
                'saldo' => max($saldo, 0),
            ];
        }

        return $tabla;
    }

    public function render()
    {
        return view('livewire.simulador-prestamo', [
            'cuotaMensual' => $this->calcularCuota(),
            'tabla' => $this->tablaAmortizacion(),
        ]);
    }
}