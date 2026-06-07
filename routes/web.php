<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProfileController;

//Controllers Cliente
use App\Http\Controllers\Cliente\DashboardClienteController;
use App\Http\Controllers\Cliente\SolicitudPrestamoController;
use App\Http\Controllers\Cliente\PrestamoController;
use App\Http\Controllers\Cliente\PagoController;
use App\Http\Controllers\Cliente\PerfilController;

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

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user?->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user?->hasRole('cliente')) {
        return redirect()->route('cliente.dashboard');
    }

    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Volt::route('/settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('/settings/password', 'settings.password')->name('settings.password');
    Volt::route('/settings/appearance', 'settings.appearance')->name('settings.appearance');
});

//Cliente
Route::middleware(['auth', 'role:cliente', 'cliente.profile.complete'])->prefix('cliente')->name('cliente.')->group(function () {

    Route::get('/dashboard', [DashboardClienteController::class, 'index'])
        ->middleware('permission:cliente.dashboard.ver')
        ->name('dashboard');

    Route::get('/simulador', [SolicitudPrestamoController::class, 'simulador'])
        ->middleware('permission:cliente.simulador.ver')
        ->name('simulador');

    Route::get('/solicitud', [SolicitudPrestamoController::class, 'create'])
        ->middleware('permission:cliente.solicitudes.crear')
        ->name('solicitud');

    Route::post('/solicitud', [SolicitudPrestamoController::class, 'store'])
        ->middleware('permission:cliente.solicitudes.crear')
        ->name('solicitud.store');

    Route::get('/confirmacion/{id}', [SolicitudPrestamoController::class, 'confirmacion'])
        ->middleware('permission:cliente.solicitudes.ver')
        ->name('confirmacion');

    Route::get('/mis-solicitudes', [SolicitudPrestamoController::class, 'index'])
        ->middleware('permission:cliente.solicitudes.ver')
        ->name('solicitudes');

    Route::get('/prestamos', [PrestamoController::class, 'index'])
        ->middleware('permission:cliente.prestamos.ver')
        ->name('prestamos');

    Route::get('/estado-cuenta-general', [PrestamoController::class, 'estadoCuentaGeneral'])
        ->middleware('permission:cliente.estado-cuenta.ver')
        ->name('estado-cuenta-general');

    Route::get('/estado-cuenta/{id}', [PrestamoController::class, 'estadoCuenta'])
        ->middleware('permission:cliente.estado-cuenta.ver')
        ->name('estado-cuenta');

    Route::get('/pagos', [PagoController::class, 'index'])
        ->middleware('permission:cliente.pagos.ver')
        ->name('pagos');

    Route::get('/pagos/create/{prestamo_id}', [PagoController::class, 'create'])
        ->middleware('permission:cliente.pagos.crear')
        ->name('pagos.create');

    Route::post('/pagos/store/{prestamo_id}', [PagoController::class, 'store'])
        ->middleware('permission:cliente.pagos.crear')
        ->name('pagos.store');
    
    Route::get('/perfil', [PerfilController::class, 'show'])
        ->middleware('permission:cliente.perfil.ver')
        ->name('perfil');

    Route::get('/perfil/editar', [PerfilController::class, 'edit'])
        ->middleware('permission:cliente.perfil.editar')
        ->name('perfil.edit');

    Route::patch('/perfil', [PerfilController::class, 'update'])
        ->middleware('permission:cliente.perfil.editar')
        ->name('perfil.update');

});

//Administrador
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardAdminController::class, 'index'])
        ->middleware('permission:admin.dashboard.ver')
        ->name('dashboard');

    //Solicitudes
    Route::get('/solicitudes', [SolicitudAdminController::class, 'index'])
        ->middleware('permission:admin.solicitudes.ver')
        ->name('solicitudes');

    Route::post('/solicitudes/{id}/aprobar', [SolicitudAdminController::class, 'aprobar'])
        ->middleware('permission:admin.solicitudes.aprobar')
        ->name('solicitudes.aprobar');

    Route::post('/solicitudes/{id}/rechazar', [SolicitudAdminController::class, 'rechazar'])
        ->middleware('permission:admin.solicitudes.rechazar')
        ->name('solicitudes.rechazar');

    // Clientes
    Route::get('/clientes', [ClienteAdminController::class, 'index'])
        ->middleware('permission:admin.clientes.ver')
        ->name('clientes');

    Route::get('/clientes/{id}', [ClienteAdminController::class, 'show'])
        ->middleware('permission:admin.clientes.ver')
        ->name('clientes.show');

    Route::get('/clientes/{id}/editar', [ClienteAdminController::class, 'edit'])
        ->middleware('permission:admin.clientes.ver')
        ->name('clientes.edit');

    Route::put('/clientes/{id}', [ClienteAdminController::class, 'update'])
        ->middleware('permission:admin.clientes.ver')
        ->name('clientes.update');

    Route::delete('/clientes/{id}', [ClienteAdminController::class, 'destroy'])
        ->middleware('permission:admin.clientes.ver')
        ->name('clientes.destroy');

    // Prestamos
    Route::get('/prestamos', [PrestamoAdminController::class, 'index'])
        ->middleware('permission:admin.prestamos.ver')
        ->name('prestamos');

    Route::get('/prestamos/create', [PrestamoAdminController::class, 'create'])
        ->middleware('permission:admin.prestamos.crear')
        ->name('prestamos.create');

    Route::post('/prestamos', [PrestamoAdminController::class, 'store'])
        ->middleware('permission:admin.prestamos.crear')
        ->name('prestamos.store');

    Route::get('/prestamos/{id}', [PrestamoAdminController::class, 'show'])
        ->middleware('permission:admin.prestamos.ver')
        ->name('prestamos.show');

    Route::get('/prestamos/{id}/editar', [PrestamoAdminController::class, 'edit'])
        ->middleware('permission:admin.prestamos.editar')
        ->name('prestamos.edit');

    Route::put('/prestamos/{id}', [PrestamoAdminController::class, 'update'])
        ->middleware('permission:admin.prestamos.editar')
        ->name('prestamos.update');

    Route::delete('/prestamos/{id}', [PrestamoAdminController::class, 'destroy'])
        ->middleware('permission:admin.prestamos.eliminar')
        ->name('prestamos.destroy');

    // Pagos
    Route::get('/pagos', [PagoAdminController::class, 'index'])
        ->middleware('permission:admin.pagos.ver')
        ->name('pagos');

    Route::get('/pagos/create', [PagoAdminController::class, 'create'])
        ->middleware('permission:admin.pagos.crear')
        ->name('pagos.create');

    Route::post('/pagos/store', [PagoAdminController::class, 'store'])
        ->middleware('permission:admin.pagos.crear')
        ->name('pagos.store');

    //Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])
        ->middleware('permission:admin.reportes.ver')
        ->name('reportes');

    
});

//Auth Breeze
require __DIR__.'/auth.php';
