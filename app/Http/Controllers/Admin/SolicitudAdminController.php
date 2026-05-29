<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolicitudPrestamo;
use App\Models\Prestamo;

class SolicitudAdminController extends Controller
{
    public function index()
    {
        $solicitudes = SolicitudPrestamo::with('user')->latest()->get();

        return view('admin.solicitudes', compact('solicitudes'));
    }

    public function aprobar($id)
    {
        $solicitud = SolicitudPrestamo::with('user')->findOrFail($id);

        $solicitud->update([
            'estado' => 'aprobada'
        ]);

        Prestamo::create([
            'user_id' => $solicitud->user_id,
            'solicitud_prestamo_id' => $solicitud->id,
            'folio' => 'PR-' . date('Y') . '-' . str_pad(Prestamo::count() + 1, 6, '0', STR_PAD_LEFT),
            'monto_total' => $solicitud->total_pagar,
            'saldo_pendiente' => $solicitud->total_pagar,
            'plazo_meses' => $solicitud->plazo_meses,
            'tasa_interes' => $solicitud->tasa_interes,
            'pago_mensual' => $solicitud->pago_mensual,
            'fecha_inicio' => now(),
            'fecha_final' => now()->addMonths($solicitud->plazo_meses),
            'estado' => 'activo',
        ]);

        return back()->with('success', 'Solicitud aprobada y préstamo creado.');
    }

    public function rechazar($id)
    {
        $solicitud = SolicitudPrestamo::findOrFail($id);

        $solicitud->update([
            'estado' => 'rechazada'
        ]);

        return back()->with('success', 'Solicitud rechazada correctamente.');
    }
}
