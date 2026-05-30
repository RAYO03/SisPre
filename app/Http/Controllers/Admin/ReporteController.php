<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Prestamo;
use App\Models\SolicitudPrestamo;
use App\Models\Pago;

class ReporteController extends Controller
{
    public function index()
    {
        $totalClientes = Cliente::count();
        $totalSolicitudes = SolicitudPrestamo::count();
        $prestamosActivos = Prestamo::where('estado', 'activo')->count();
        $prestamosPagados = Prestamo::where('estado', 'pagado')->count();
        $totalPrestado = Prestamo::sum('monto_total');
        $totalCobrado = Pago::where('estado', 'pagado')->sum('monto');

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
