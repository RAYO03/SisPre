<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;
use App\Models\SolicitudPrestamo;
use App\Models\Pago;
use App\Support\Estado;
use Illuminate\Support\Facades\Auth;


class DashboardClienteController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $prestamoActivo = Prestamo::where('user_id', $user->id)
            ->where('estado', Estado::ACTIVO)
            ->latest()
            ->first();

        $solicitudes = SolicitudPrestamo::where('user_id', $user->id)
            ->latest()
            ->get();

        $pagos = Pago::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('cliente.dashboard', compact(
            'prestamoActivo',
            'solicitudes',
            'pagos'
        ));
    }
}
