<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;
use Illuminate\Support\Facades\Auth;

class PrestamoController extends Controller
{
    public function index()
    {
        $prestamos = Prestamo::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('cliente.mis-prestamos', compact('prestamos'));
    }

    public function estadoCuenta($id)
    {
        $prestamo = Prestamo::where('user_id', Auth::id())
            ->with('pagos')
            ->findOrFail($id);

        return view('cliente.estado-cuenta', compact('prestamo'));
    }
}
