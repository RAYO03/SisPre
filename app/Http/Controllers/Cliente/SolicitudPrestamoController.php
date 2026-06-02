<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\SolicitudPrestamo;
use App\Services\AmortizacionService;
use App\Support\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

    public function store(Request $request, AmortizacionService $amortizacion)
    {
        $motivos = ['Emergencia', 'Negocio', 'Personal'];
        $tiposEmpleo = ['Empleado', 'Independiente', 'Negocio propio'];
        $antiguedades = [
            'Menos de 6 meses',
            '6 meses a 1 año',
            '1 a 2 años',
            'Más de 2 años',
        ];

        $request->validate([
            'monto_solicitado' => ['required', 'numeric', 'min:1000'],
            'plazo_meses' => ['required', 'integer', Rule::in($amortizacion->plazosPermitidos())],
            'motivo' => ['required', 'string', Rule::in($motivos)],
            'ingreso_mensual' => ['required', 'numeric', 'min:1'],
            'tipo_empleo' => ['required', 'string', Rule::in($tiposEmpleo)],
            'antiguedad_laboral' => ['required', 'string', Rule::in($antiguedades)],
        ]);

        $resumen = $amortizacion->generarResumen(
            (float) $request->monto_solicitado,
            (int) $request->plazo_meses,
            now()
        );

        $solicitud = SolicitudPrestamo::create([
            'user_id' => Auth::id(),
            'folio' => 'SOL-' . date('Y') . '-' . str_pad(SolicitudPrestamo::count() + 1, 6, '0', STR_PAD_LEFT),
            'monto_solicitado' => $request->monto_solicitado,
            'plazo_meses' => $request->plazo_meses,
            'tasa_interes' => $resumen['tasa_anual'],
            'pago_mensual' => $resumen['pago_mensual'],
            'total_pagar' => $resumen['total_pagar'],
            'motivo' => $request->motivo,
            'ingreso_mensual' => $request->ingreso_mensual,
            'tipo_empleo' => $request->tipo_empleo,
            'antiguedad_laboral' => $request->antiguedad_laboral,
            'estado' => Estado::SOLICITADO,
        ]);

        return redirect()
            ->route('cliente.solicitudes')
            ->with('success', 'Solicitud enviada correctamente. Folio: ' . $solicitud->folio);
    }

    public function confirmacion($id)
    {
        $solicitud = SolicitudPrestamo::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('cliente.confirmacion', compact('solicitud'));
    }
}
