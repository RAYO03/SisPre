<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Prestamo;
use App\Models\SolicitudPrestamo;
use App\Models\Pago;
use App\Support\Estado;
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

        $totalClientes = Cliente::whereBetween('created_at', [$inicio, $fin])->count();

        $solicitudesPendientes = SolicitudPrestamo::where('estado', Estado::SOLICITADO)
            ->whereBetween('created_at', [$inicio, $fin])
            ->count();

        $prestamosActivos = Prestamo::where('estado', Estado::ACTIVO)
            ->whereBetween('created_at', [$inicio, $fin])
            ->count();

        $pagosMes = Pago::whereBetween('fecha_pago', [$inicio, $fin])
            ->sum('monto');

        $prestamosSemana = Prestamo::whereBetween('created_at', [$inicio, $fin])
            ->get()
            ->groupBy(fn (Prestamo $prestamo) => Carbon::parse($prestamo->created_at)->weekOfYear)
            ->map->count();

        $prestamosPorEstado = collect([
            Estado::ACTIVO => Prestamo::where('estado', Estado::ACTIVO)
                ->whereBetween('created_at', [$inicio, $fin])
                ->count(),

            Estado::LIQUIDADO => Prestamo::where('estado', Estado::LIQUIDADO)
                ->whereBetween('created_at', [$inicio, $fin])
                ->count(),

            Estado::EN_MORA => Prestamo::where('estado', Estado::EN_MORA)
                ->whereBetween('created_at', [$inicio, $fin])
                ->count(),
        ]);
        $prestamosPorEstadoLabels = $prestamosPorEstado
            ->keys()
            ->map(fn ($estado) => Estado::label($estado));

        return view('admin.dashboard', compact(
            'totalClientes',
            'solicitudesPendientes',
            'prestamosActivos',
            'pagosMes',
            'fechaInicio',
            'fechaFin',
            'prestamosSemana',
            'prestamosPorEstado',
            'prestamosPorEstadoLabels'
        ));
    }
}
