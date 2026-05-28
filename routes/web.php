<?php

use App\Http\Controllers\SolicitudPrestamoController;
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

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::middleware(['auth', 'role:usuario'])->group(function () {

    Route::get('/mis-prestamos', [PrestamoController::class, 'misPrestamos'])
        ->name('usuario.prestamos');

    Route::get('/mis-pagos', [PagoController::class, 'misPagos'])
        ->name('usuario.pagos');

    Route::get('/solicitar-prestamo', [SolicitudPrestamoController::class, 'create'])
        ->name('usuario.prestamos.create');

    Route::post('/solicitar-prestamo', [SolicitudPrestamoController::class, 'store'])
        ->name('usuario.prestamos.store');
});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::resource('clientes', ClienteController::class);

    Route::get('/admin/solicitudes', [SolicitudPrestamoController::class, 'index'])
        ->name('admin.solicitudes.index');

    Route::get('/admin/solicitudes/{solicitud}', [SolicitudPrestamoController::class, 'show'])
        ->name('admin.solicitudes.show');

    Route::post('/admin/solicitudes/{solicitud}/aprobar', [SolicitudPrestamoController::class, 'aprobar'])
        ->name('admin.solicitudes.aprobar');

    Route::post('/admin/solicitudes/{solicitud}/rechazar', [SolicitudPrestamoController::class, 'rechazar'])
        ->name('admin.solicitudes.rechazar');

    Route::patch('/prestamos/{prestamo}/activar', [PrestamoController::class, 'activar'])
        ->name('prestamos.activar');

    Route::resource('prestamos', PrestamoController::class);

    Route::resource('pagos', PagoController::class);
});

require __DIR__.'/auth.php';