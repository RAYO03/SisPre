<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AmortizacionService;
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

    private function generarResumen(): array
    {
        $capital = floatval($this->capital);
        $plazo = intval($this->plazo_meses);

        if ($capital <= 0 || $plazo <= 0) {
            return [
                'tasa_anual' => 0,
                'pago_mensual' => 0,
                'total_pagar' => 0,
                'tabla' => [],
            ];
        }

        return app(AmortizacionService::class)->generarResumen(
            $capital,
            $plazo,
            Carbon::parse($this->fecha_inicio ?: now())
        );
    }

    public function render()
    {
        $resumen = $this->generarResumen();

        return view('livewire.simulador-prestamo', [
            'plazos' => app(AmortizacionService::class)->plazosPermitidos(),
            'resumen' => $resumen,
            'tabla' => $resumen['tabla'],
        ]);
    }
}
