<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;

class PrestamoAdminController extends Controller
{
    public function index()
    {
        $prestamos = Prestamo::with('user')->latest()->get();

        return view('admin.prestamos', compact('prestamos'));
    }

    public function show($id)
    {
        $prestamo = Prestamo::with(['user', 'pagos', 'solicitud'])->findOrFail($id);

        return view('admin.prestamo-detalle', compact('prestamo'));
    }
}
