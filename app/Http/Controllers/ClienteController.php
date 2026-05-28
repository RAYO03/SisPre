<?php

namespace App\Http\Controllers;

use App\Models\User;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = User::where('role', 'usuario')
            ->paginate(15);

        return view('clientes.index', compact('clientes'));
    }

    public function show(User $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }
}