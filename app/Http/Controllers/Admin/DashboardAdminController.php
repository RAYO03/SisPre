<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Prestamo;
use App\Models\SolicitudPrestamo;
use App\Models\Pago;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->input('mes', now()->format('Y-m'));

        $inicio = Carbon::parse($mes . '-01')->startOfMonth();
        $fin = Carbon::parse($mes . '-01')->endOfMonth();

        $fechaInicio = $inicio->format('Y-m-d');
        $fechaFin = $fin->format('Y-m-d');

        $totalClientes = Cliente::count();

        $solicitudesPendientes = SolicitudPrestamo::where('estado', 'pendiente')
            ->whereBetween('created_at', [$inicio, $fin])
            ->count();

        $prestamosActivos = Prestamo::where('estado', 'activo')
            ->whereBetween('created_at', [$inicio, $fin])
            ->count();

        $pagosMes = Pago::whereBetween('fecha_pago', [$inicio, $fin])
            ->sum('monto');

        $prestamosSemana = Prestamo::selectRaw('WEEK(created_at) as semana, COUNT(*) as total')
            ->whereBetween('created_at', [$inicio, $fin])
            ->groupBy('semana')
            ->orderBy('semana')
            ->pluck('total', 'semana');

        $prestamosPorEstado = collect([
            'activo' => Prestamo::where('estado', 'activo')
                ->whereBetween('created_at', [$inicio, $fin])
                ->count(),

            'pagado' => Prestamo::where('estado', 'pagado')
                ->whereBetween('created_at', [$inicio, $fin])
                ->count(),

            'vencido' => Prestamo::where('estado', 'vencido')
                ->whereBetween('created_at', [$inicio, $fin])
                ->count(),
        ]);

        return view('admin.dashboard', compact(
            'totalClientes',
            'solicitudesPendientes',
            'prestamosActivos',
            'pagosMes',
            'fechaInicio',
            'fechaFin',
            'prestamosSemana',
            'prestamosPorEstado'
        ));
    }
}