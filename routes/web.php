<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\PagoController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // PERFIL
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CLIENTES
    Route::resource('clientes', ClienteController::class);

    // PRÉSTAMOS
    Route::resource('prestamos', PrestamoController::class);
    Route::patch('prestamos/{prestamo}/aprobar', [PrestamoController::class, 'aprobar'])->name('prestamos.aprobar');
    Route::patch('prestamos/{prestamo}/activar', [PrestamoController::class, 'activar'])->name('prestamos.activar');

    // PAGOS
    
Route::get('pagos/create/{prestamo}', [PagoController::class, 'create'])->name('pagos.create');
Route::post('pagos/{prestamo}', [PagoController::class, 'store'])->name('pagos.store');

Route::resource('pagos', PagoController::class)->except(['create', 'store']);

});

require __DIR__ . '/auth.php';
