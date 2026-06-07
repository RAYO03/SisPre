<?php
use App\Services\PagoService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    app(PagoService::class)->marcarCuotasVencidas();
})->daily();