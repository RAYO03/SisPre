<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Services\AmortizacionService;
use App\Services\PagoService;
use App\Support\Estado;
use App\Support\PrestamoConfig;
use Carbon\Carbon;
use Illuminate\Validation\Rule;


class PagoAdminController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['user', 'prestamo'])
            ->latest('fecha_pago')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pagos', compact('pagos'));
    }

    public function create(Request $request, AmortizacionService $amortizacion, PagoService $pagoService)
    {
        $pagoService->marcarCuotasVencidas();

        $prestamos = Prestamo::with('user')
            ->whereIn('estado', [Estado::ACTIVO, Estado::EN_MORA])
            ->orderBy('folio')
            ->get();

        $prestamoSeleccionado = null;
        $resumenPago = null;
        $montoSugerido = 0;
        $montoMaximo = 0;
        $moraPendiente = 0;
        $cuotasResumen = collect();
        $fechaPagoSeleccionada = $request->query('fecha_pago', now()->toDateString());
        $metodosPago = PrestamoConfig::metodosPago();

        if ($prestamos->isNotEmpty()) {
            $prestamoSeleccionado = Prestamo::with(['user', 'cuotas'])
                ->whereIn('estado', [Estado::ACTIVO, Estado::EN_MORA])
                ->find($request->query('prestamo_id') ?? $prestamos->first()->id);

            if ($prestamoSeleccionado) {
                if ($prestamoSeleccionado->cuotas->isEmpty()) {
                    $amortizacion->guardarTabla($prestamoSeleccionado);
                    $prestamoSeleccionado->load('cuotas');
                }

                $pagoService->actualizarEstadoPrestamo($prestamoSeleccionado);
                $prestamoSeleccionado->refresh()->load(['user', 'cuotas']);

                $fechaPago = Carbon::parse($fechaPagoSeleccionada);
                $siguienteCuota = $pagoService->siguienteCuotaParaPago($prestamoSeleccionado);
                $resumenPago = $pagoService->resumenSiguientePago($prestamoSeleccionado, $siguienteCuota, $fechaPago);
                $montoSugerido = $resumenPago['total_sugerido'];
                $montoMaximo = $pagoService->montoTotalPendienteParaPago($prestamoSeleccionado, $fechaPago);
                $moraPendiente = $pagoService->moraPendienteParaPago($prestamoSeleccionado, $fechaPago);
                $cuotasResumen = $pagoService->cuotasParaResumen(
                    $prestamoSeleccionado,
                    $prestamoSeleccionado->cuotas->count(),
                    $fechaPago
                );
            }
        }

        return view('admin.registrar-pago', compact(
            'prestamos',
            'prestamoSeleccionado',
            'resumenPago',
            'montoSugerido',
            'montoMaximo',
            'moraPendiente',
            'cuotasResumen',
            'fechaPagoSeleccionada',
            'metodosPago'
        ));
    }

    public function store(Request $request, AmortizacionService $amortizacion, PagoService $pagoService)
    {
        $validated = $request->validate([
            'prestamo_id' => 'required|exists:prestamos,id',
            'monto' => 'required|numeric|min:0.01',
            'metodo_pago' => ['required', Rule::in(PrestamoConfig::metodosPago())],
            'fecha_pago' => 'required|date|before_or_equal:today',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $prestamo = Prestamo::with('cuotas')
            ->whereIn('estado', [Estado::ACTIVO, Estado::EN_MORA])
            ->findOrFail($validated['prestamo_id']);

        if ($prestamo->cuotas->isEmpty()) {
            $amortizacion->guardarTabla($prestamo);
            $prestamo->load('cuotas');
        }

        $fechaPago = Carbon::parse($validated['fecha_pago']);
        $montoMaximo = $pagoService->montoTotalPendienteParaPago($prestamo, $fechaPago);

        $request->validate([
            'monto' => ['max:' . $montoMaximo],
        ]);

        $comprobante = $request->file('comprobante')
            ? $request->file('comprobante')->store('comprobantes', 'public')
            : null;

        $pagoService->registrarPago(
            $prestamo,
            (float) $validated['monto'],
            $fechaPago,
            $validated['metodo_pago'],
            $prestamo->user_id,
            $comprobante
        );

        if ($request->has('registrar_otro')) {
            return redirect()
                ->route('admin.pagos.create')
                ->with('success', 'Pago registrado correctamente.');
        }

        return redirect()
            ->route('admin.pagos')
            ->with('success', 'Pago registrado correctamente.');
    }
}
