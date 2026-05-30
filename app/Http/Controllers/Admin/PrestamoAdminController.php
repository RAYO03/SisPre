<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;

class PrestamoAdminController extends Controller
{
    public function index()
    {
        $prestamos = Prestamo::with('user')
            ->where('estado', 'activo')
            ->where(function ($query) {
                $query->whereDoesntHave('solicitud')
                    ->orWhereHas('solicitud', fn ($solicitud) => $solicitud->where('estado', 'aprobada'));
            })
            ->latest()
            ->get();

        return view('admin.prestamos', compact('prestamos'));
    }

    public function show($id)
    {
        $prestamo = Prestamo::with(['user', 'pagos', 'solicitud'])
            ->where('estado', 'activo')
            ->where(function ($query) {
                $query->whereDoesntHave('solicitud')
                    ->orWhereHas('solicitud', fn ($solicitud) => $solicitud->where('estado', 'aprobada'));
            })
            ->findOrFail($id);

        return view('admin.prestamo-detalle', compact('prestamo'));
    }
}
