<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AmortizacionService;
use App\Support\PrestamoConfig;
use Carbon\Carbon;

class SimuladorPrestamo extends Component
{
    public $capital = 0;
    public $plazo_meses = 12;
    public $fecha_inicio;

    public function mount()
    {
        $this->fecha_inicio = date('Y-m-d');
    }

    public function continuarSolicitud()
    {
        if (! $this->puedeContinuar()) {
            return null;
        }

        return $this->redirectRoute('cliente.solicitud', [
            'monto_solicitado' => (float) $this->capital,
            'plazo_meses' => (int) $this->plazo_meses,
        ]);
    }

    private function generarResumen(): array
    {
        $capital = floatval($this->capital);
        $plazo = intval($this->plazo_meses);
        $fechaInicio = $this->fechaInicioValida();

        if ($capital < PrestamoConfig::MONTO_MINIMO || $capital > PrestamoConfig::MONTO_MAXIMO || $plazo <= 0 || ! $fechaInicio) {
            return [
                'tasa_anual' => 0,
                'pago_mensual' => 0,
                'pago_final' => 0,
                'ajuste_redondeo' => 0,
                'total_pagar' => 0,
                'tabla' => [],
            ];
        }

        return app(AmortizacionService::class)->generarResumen(
            $capital,
            $plazo,
            $fechaInicio
        );
    }

    private function fechaInicioValida(): ?Carbon
    {
        if (! is_string($this->fecha_inicio) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->fecha_inicio)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $this->fecha_inicio)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    private function puedeContinuar(): bool
    {
        $capital = floatval($this->capital);
        $plazo = intval($this->plazo_meses);

        return $capital >= PrestamoConfig::MONTO_MINIMO
            && $capital <= PrestamoConfig::MONTO_MAXIMO
            && $plazo > 0
            && $this->fechaInicioValida() !== null;
    }

    public function render()
    {
        $resumen = $this->generarResumen();
        $fechaValida = $this->fechaInicioValida() !== null;
        $montoValido = floatval($this->capital) >= PrestamoConfig::MONTO_MINIMO
            && floatval($this->capital) <= PrestamoConfig::MONTO_MAXIMO;

        return view('livewire.simulador-prestamo', [
            'plazos' => app(AmortizacionService::class)->plazosPermitidos(),
            'resumen' => $resumen,
            'tabla' => $resumen['tabla'],
            'montoMinimo' => PrestamoConfig::MONTO_MINIMO,
            'montoMaximo' => PrestamoConfig::MONTO_MAXIMO,
            'montoValido' => $montoValido,
            'fechaValida' => $fechaValida,
            'puedeContinuar' => $this->puedeContinuar(),
        ]);
    }
}
