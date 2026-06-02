<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Prestamo;
use App\Models\SolicitudPrestamo;
use App\Models\Pago;
use App\Support\Estado;

class ReporteController extends Controller
{
    public function index()
    {
        $totalClientes = Cliente::count();
        $totalSolicitudes = SolicitudPrestamo::count();
        $prestamosActivos = Prestamo::where('estado', Estado::ACTIVO)->count();
        $prestamosPagados = Prestamo::where('estado', Estado::LIQUIDADO)->count();
        $totalPrestado = Prestamo::sum('monto_total');
        $totalCobrado = Pago::where('estado', Estado::LIQUIDADO)->sum('monto');

        return view('admin.reportes', compact(
            'totalClientes',
            'totalSolicitudes',
            'prestamosActivos',
            'prestamosPagados',
            'totalPrestado',
            'totalCobrado'
        ));
    }
}
