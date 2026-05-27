<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PagoService;

class MarcarCuotasVencidas extends Command
{
    protected $signature   = 'prestamos:marcar-vencidas';
    protected $description = 'Marca como vencidas las cuotas no pagadas';

    public function handle(PagoService $pagoService): void
    {
        $pagoService->marcarCuotasVencidas();
        $this->info('Cuotas vencidas actualizadas correctamente.');
    }
}