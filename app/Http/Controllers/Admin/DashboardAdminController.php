<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;
use App\Models\SolicitudPrestamo;
use App\Models\Pago;
use App\Models\User;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $totalClientes = User::where('tipo_usuario', 'cliente')->count();
        $solicitudesPendientes = SolicitudPrestamo::where('estado', 'pendiente')->count();
        $prestamosActivos = Prestamo::where('estado', 'activo')->count();
        $pagosMes = Pago::whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->sum('monto');

        return view('admin.dashboard', compact(
            'totalClientes',
            'solicitudesPendientes',
            'prestamosActivos',
            'pagosMes'
        ));
    }
}
