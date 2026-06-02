<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PagoService;

class MarcarCuotasVencidas extends Command
{
    protected $signature   = 'prestamos:marcar-vencidas';
    protected $description = 'Marca en mora las cuotas no liquidadas';

    public function handle(PagoService $pagoService): void
    {
        $pagoService->marcarCuotasVencidas();
        $this->info('Cuotas en mora actualizadas correctamente.');
    }
}
