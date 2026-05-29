<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;

class ClienteAdminController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('user')->latest()->get();

        return view('admin.clientes', compact('clientes'));
    }

    public function show($id)
    {
        $cliente = Cliente::with([
            'user.solicitudes',
            'user.prestamos',
            'user.pagos'
        ])->findOrFail($id);

        return view('admin.cliente-detalle', compact('cliente'));
    }
}
