<?php

use Illuminate\Support\Facades\Route;


//Controllers Cliente
use App\Http\Controllers\Cliente\DashboardClienteController;
use App\Http\Controllers\Cliente\SolicitudPrestamoController;
use App\Http\Controllers\Cliente\PrestamoController;
use App\Http\Controllers\Cliente\PagoController;

//Controllers Admin
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\SolicitudAdminController;
use App\Http\Controllers\Admin\ClienteAdminController;
use App\Http\Controllers\Admin\PrestamoAdminController;
use App\Http\Controllers\Admin\PagoAdminController;
use App\Http\Controllers\Admin\ReporteController;

//Página principal
Route::get('/', function () {
    return view('welcome');
})->name('inicio');

//Cliente
Route::middleware(['auth'])->prefix('cliente')->name('cliente.')->group(function () {

    Route::get('/dashboard', [DashboardClienteController::class, 'index'])
        ->name('dashboard');

    Route::get('/simulador', [SolicitudPrestamoController::class, 'simulador'])
        ->name('simulador');

    Route::get('/solicitud', [SolicitudPrestamoController::class, 'create'])
        ->name('solicitud');

    Route::post('/solicitud', [SolicitudPrestamoController::class, 'store'])
        ->name('solicitud.store');

    Route::get('/confirmacion/{id}', [SolicitudPrestamoController::class, 'confirmacion'])
        ->name('confirmacion');

    Route::get('/mis-solicitudes', [SolicitudPrestamoController::class, 'index'])
        ->name('solicitudes');

    Route::get('/prestamos', [PrestamoController::class, 'index'])
        ->name('prestamos');

    Route::get('/estado-cuenta/{id}', [PrestamoController::class, 'estadoCuenta'])
        ->name('estado-cuenta');

    Route::get('/pagos', [PagoController::class, 'index'])
        ->name('pagos');

    Route::get('/pagos/create/{prestamo_id}', [PagoController::class, 'create'])
        ->name('pagos.create');

    Route::post('/pagos/store/{prestamo_id}', [PagoController::class, 'store'])
        ->name('pagos.store');
    
    Route::get('/perfil', function () {
        return view('cliente.perfil');
    })->name('perfil');

});

//Administrador
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardAdminController::class, 'index'])
        ->name('dashboard');

    //Solicitudes
    Route::get('/solicitudes', [SolicitudAdminController::class, 'index'])
        ->name('solicitudes');

    Route::post('/solicitudes/{id}/aprobar', [SolicitudAdminController::class, 'aprobar'])
        ->name('solicitudes.aprobar');

    Route::post('/solicitudes/{id}/rechazar', [SolicitudAdminController::class, 'rechazar'])
        ->name('solicitudes.rechazar');

    // Clientes
    Route::get('/clientes', [ClienteAdminController::class, 'index'])
        ->name('clientes');

    Route::get('/clientes/{id}', [ClienteAdminController::class, 'show'])
        ->name('clientes.show');

    // Prestamos
    Route::get('/prestamos', [PrestamoAdminController::class, 'index'])
        ->name('prestamos');

    Route::get('/prestamos/{id}', [PrestamoAdminController::class, 'show'])
        ->name('prestamos.show');

    // Pagos
    Route::get('/pagos', [PagoAdminController::class, 'index'])
        ->name('pagos');

    Route::get('/pagos/create', [PagoAdminController::class, 'create'])
        ->name('pagos.create');

    Route::post('/pagos/store', [PagoAdminController::class, 'store'])
        ->name('pagos.store');

    //Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])
        ->name('reportes');
});

//Auth Breeze
require __DIR__.'/auth.php';