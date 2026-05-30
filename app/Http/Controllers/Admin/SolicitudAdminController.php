<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolicitudPrestamo;
use App\Models\Prestamo;
use Illuminate\Support\Facades\DB;

class SolicitudAdminController extends Controller
{
    public function index()
    {
        $solicitudes = SolicitudPrestamo::with('user')->latest()->get();

        return view('admin.solicitudes', compact('solicitudes'));
    }

    public function aprobar($id)
    {
        $procesada = DB::transaction(function () use ($id) {
            $solicitud = SolicitudPrestamo::with('user')->lockForUpdate()->findOrFail($id);

            if ($solicitud->estado !== 'pendiente') {
                return false;
            }

            $solicitud->update([
                'estado' => 'aprobada'
            ]);
            $ultimoPrestamo = Prestamo::latest('id')->first();

            $numero = $ultimoPrestamo
        ? intval(substr($ultimoPrestamo->folio, -6)) + 1
        : 1;

            Prestamo::firstOrCreate([
                'solicitud_prestamo_id' => $solicitud->id,
            ], [
                'user_id' => $solicitud->user_id,
                'folio' => 'PR-' . now()->year . '-' . str_pad($numero, 6, '0', STR_PAD_LEFT),
                'monto_total' => $solicitud->total_pagar,
                'saldo_pendiente' => $solicitud->total_pagar,
                'plazo_meses' => $solicitud->plazo_meses,
                'tasa_interes' => $solicitud->tasa_interes,
                'pago_mensual' => $solicitud->pago_mensual,
                'fecha_inicio' => now(),
                'fecha_final' => now()->addMonths($solicitud->plazo_meses),
                'estado' => 'activo',
            ]);

            return true;
        });

        if (! $procesada) {
            return back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        return back()->with('success', 'Solicitud aprobada y préstamo creado.');
    }

    public function rechazar($id)
    {
        $procesada = DB::transaction(function () use ($id) {
            $solicitud = SolicitudPrestamo::lockForUpdate()->findOrFail($id);

            if ($solicitud->estado !== 'pendiente') {
                return false;
            }

            $solicitud->update([
                'estado' => 'rechazada'
            ]);

            return true;
        });

        if (! $procesada) {
            return back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        return back()->with('success', 'Solicitud rechazada correctamente.');
    }
}
