<?php

namespace App\Http\Controllers\Admin;

use App\Console\Commands\MarcarCuotasVencidas;
use App\Http\Controllers\Controller;
use App\Models\Cuota;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Services\PagoService;
use App\Support\Estado;


class ReporteController extends Controller
{
    public function index(PagoService $pagoService)
    {
        $pagoService->marcarCuotasVencidas();

        $totalPrestado = Prestamo::selectRaw('SUM(COALESCE(monto_original, monto_total)) as total')
            ->value('total') ?? 0;

        $totalCobrado = Pago::where('estado', Estado::LIQUIDADO)->sum('monto');

        $saldoPendiente = Prestamo::whereIn('estado', [Estado::ACTIVO, Estado::EN_MORA])
            ->sum('saldo_pendiente');

        $carteraMora = Cuota::whereDate('fecha_vencimiento', '<', today())
            ->where('estado', '!=', Estado::PAGADA)
            ->selectRaw('SUM(CASE WHEN cuota_total - monto_pagado > 0 THEN cuota_total - monto_pagado ELSE 0 END) as total')
            ->value('total') ?? 0;

        $pagosMes = Pago::where('estado', Estado::LIQUIDADO)
            ->whereBetween('fecha_pago', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('monto');

        $prestamosPorEstado = [
            'activos' => Prestamo::where('estado', Estado::ACTIVO)->count(),
            'liquidados' => Prestamo::where('estado', Estado::LIQUIDADO)->count(),
            'en_mora' => Prestamo::where('estado', Estado::EN_MORA)->count(),
        ];

        $alertasCobranza = Cuota::with('prestamo.user')
            ->whereDate('fecha_vencimiento', '<', today())
            ->where('estado', '!=', Estado::PAGADA)
            ->orderBy('fecha_vencimiento')
            ->limit(5)
            ->get();

        $ultimosPagos = Pago::with('user')
            ->where('estado', Estado::LIQUIDADO)
            ->latest('fecha_pago')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('admin.reportes', compact(
            'totalPrestado',
            'totalCobrado',
            'saldoPendiente',
            'carteraMora',
            'pagosMes',
            'prestamosPorEstado',
            'alertasCobranza',
            'ultimosPagos'
        ));
    }
}
