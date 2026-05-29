<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SolicitudPrestamo;
use Illuminate\Support\Facades\Auth;

class SolicitudPrestamoController extends Controller
{
    public function index()
    {
        $solicitudes = SolicitudPrestamo::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('cliente.mis-solicitudes', compact('solicitudes'));
    }

    public function simulador()
    {
        return view('cliente.simulador');
    }

    public function create()
    {
        return view('cliente.solicitud');
    }

    public function store(Request $request)
    {
        $request->validate([
            'monto_solicitado' => 'required|numeric|min:1000',
            'plazo_meses' => 'required|integer|min:1',
            'motivo' => 'nullable|string',
            'ingreso_mensual' => 'required|numeric|min:1',
            'tipo_empleo' => 'required|string|max:255',
            'antiguedad_laboral' => 'required|string|max:255',
        ]);

        $tasa = 19.9;
        $interes = $request->monto_solicitado * ($tasa / 100);
        $totalPagar = $request->monto_solicitado + $interes;
        $pagoMensual = $totalPagar / $request->plazo_meses;

        $solicitud = SolicitudPrestamo::create([
            'user_id' => Auth::id(),
            'folio' => 'CR-' . date('Y') . '-' . str_pad(SolicitudPrestamo::count() + 1, 6, '0', STR_PAD_LEFT),
            'monto_solicitado' => $request->monto_solicitado,
            'plazo_meses' => $request->plazo_meses,
            'tasa_interes' => $tasa,
            'pago_mensual' => $pagoMensual,
            'total_pagar' => $totalPagar,
            'motivo' => $request->motivo,
            'ingreso_mensual' => $request->ingreso_mensual,
            'tipo_empleo' => $request->tipo_empleo,
            'antiguedad_laboral' => $request->antiguedad_laboral,
            'estado' => 'pendiente',
        ]);

        return redirect()
            ->route('cliente.confirmacion', $solicitud->id);
    }

    public function confirmacion($id)
    {
        $solicitud = SolicitudPrestamo::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('cliente.confirmacion', compact('solicitud'));
    }
}
