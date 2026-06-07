<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Services\AmortizacionService;
use App\Services\PagoService;
use App\Support\Estado;
use App\Support\PrestamoConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::where('user_id', Auth::id())
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $prestamosActivos = Prestamo::where('user_id', Auth::id())
            ->whereIn('estado', [Estado::ACTIVO, Estado::EN_MORA])
            ->latest()
            ->get();

        return view('cliente.pagos', compact('pagos', 'prestamosActivos'));
    }

    public function create(Request $request, $prestamo_id, AmortizacionService $amortizacion, PagoService $pagoService)
    {
        $pagoService->marcarCuotasVencidas();

        $prestamo = Prestamo::where('user_id', Auth::id())
            ->with('cuotas')
            ->findOrFail($prestamo_id);

        if ($prestamo->cuotas->isEmpty()) {
            $amortizacion->guardarTabla($prestamo);
            $prestamo->load('cuotas');
        }

        $pagoService->actualizarEstadoPrestamo($prestamo);
        $prestamo->refresh()->load('cuotas');

        if ($prestamo->estado === Estado::LIQUIDADO) {
            return redirect()
                ->route('cliente.estado-cuenta', $prestamo->id)
                ->with('success', 'Este prestamo ya esta liquidado.');
        }

        $returnTo = $request->query('return_to');
        $siguienteCuota = $pagoService->siguienteCuotaParaPago($prestamo);
        $resumenPago = $pagoService->resumenSiguientePago($prestamo, $siguienteCuota);
        $montoSugerido = $resumenPago['total_sugerido'];
        $montoMaximo = $pagoService->montoTotalPendienteParaPago($prestamo);
        $moraPendiente = $pagoService->moraPendienteParaPago($prestamo);

        $metodosPago = PrestamoConfig::metodosPago();

        return view('cliente.realizar-pago', compact('prestamo', 'returnTo', 'montoSugerido', 'montoMaximo', 'moraPendiente', 'siguienteCuota', 'resumenPago', 'metodosPago'));
    }

    public function store(Request $request, $prestamo_id, AmortizacionService $amortizacion, PagoService $pagoService)
    {
        $pagoService->marcarCuotasVencidas();

        $prestamo = Prestamo::where('user_id', Auth::id())
            ->with('cuotas')
            ->findOrFail($prestamo_id);

        $pagoService->actualizarEstadoPrestamo($prestamo);
        $prestamo->refresh()->load('cuotas');

        if ($prestamo->estado === Estado::LIQUIDADO) {
            return redirect()
                ->route('cliente.estado-cuenta', $prestamo->id)
                ->with('success', 'Este prestamo ya esta liquidado.');
        }

        $montoMaximo = $pagoService->montoTotalPendienteParaPago($prestamo);

        $request->validate([
            'monto' => ['required', 'numeric', 'min:0.01', 'max:' . $montoMaximo],
            'metodo_pago' => ['required', Rule::in(PrestamoConfig::metodosPago())],
            'fecha_pago' => 'required|date|before_or_equal:today',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'return_to' => 'nullable|in:estado-cuenta,pagos',
        ]);

        if ($prestamo->cuotas->isEmpty()) {
            $amortizacion->guardarTabla($prestamo);
            $prestamo->load('cuotas');
        }

        $comprobante = $request->file('comprobante')
            ? $request->file('comprobante')->store('comprobantes', 'public')
            : null;

        $pagoService->registrarPago(
            $prestamo,
            (float) $request->monto,
            \Carbon\Carbon::parse($request->fecha_pago),
            $request->metodo_pago,
            Auth::id(),
            $comprobante
        );

        if ($request->return_to === 'estado-cuenta') {
            return redirect()
                ->route('cliente.estado-cuenta', $prestamo->id)
                ->with('success', 'Pago registrado correctamente. La tabla de amortizacion fue actualizada.');
        }

        return redirect()
            ->route('cliente.pagos')
            ->with('success', 'Pago registrado correctamente.');
    }
}
