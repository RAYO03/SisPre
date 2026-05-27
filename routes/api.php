<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

// API solo para respuestas JSON, no para vistas Blade
// Route::apiResource('clientes', ClienteController::class);

// Estado de cuenta por API, solo si todavía lo usas
Route::get('/clientes/{id}/estado-cuenta', [ClienteController::class, 'estadoCuenta']);