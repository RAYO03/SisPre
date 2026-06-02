<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class ClienteAdminController extends Controller
{
    public function index()
    {
        $clientes = User::with('cliente')
            ->role('cliente')
            ->latest()
            ->get();

        return view('admin.clientes', compact('clientes'));
    }

    public function show($id)
    {
        $user = User::with([
            'cliente',
            'solicitudes',
            'prestamos',
            'pagos'
        ])
            ->role('cliente')
            ->findOrFail($id);

        return view('admin.cliente-detalle', [
            'user' => $user,
            'cliente' => $user->cliente,
        ]);
    }
}
