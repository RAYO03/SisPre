<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;
use App\Models\User;
use App\Services\AmortizacionService;
use App\Support\Estado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PrestamoAdminController extends Controller
{
    public function index()
    {
        $prestamos = Prestamo::with(['user', 'solicitud'])
            ->where('estado', Estado::ACTIVO)
            ->where(function ($query) {
                $query->whereDoesntHave('solicitud')
                    ->orWhereHas('solicitud', fn ($solicitud) => $solicitud->where('estado', Estado::APROBADO));
            })
            ->latest()
            ->get();

        return view('admin.prestamos', compact('prestamos'));
    }

    public function create(AmortizacionService $amortizacion)
    {
        $clientes = User::role('cliente')
            ->orderBy('name')
            ->get();

        $plazos = $amortizacion->plazosPermitidos();

        return view('admin.prestamos-create', compact('clientes', 'plazos'));
    }

    public function store(Request $request, AmortizacionService $amortizacion)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'monto_original' => ['required', 'numeric', 'min:1000', 'max:1000000'],
            'plazo_meses' => ['required', 'integer', Rule::in($amortizacion->plazosPermitidos())],
            'fecha_inicio' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $cliente = User::role('cliente')
            ->findOrFail($validated['user_id']);

        $fechaInicio = Carbon::parse($validated['fecha_inicio']);
        $resumen = $amortizacion->generarResumen(
            (float) $validated['monto_original'],
            (int) $validated['plazo_meses'],
            $fechaInicio
        );

        $prestamo = DB::transaction(function () use ($cliente, $validated, $fechaInicio, $resumen, $amortizacion) {
            $ultimoPrestamo = Prestamo::whereYear('created_at', now()->year)
                ->latest('id')
                ->first();

            $numero = $ultimoPrestamo
                ? intval(substr($ultimoPrestamo->folio, -6)) + 1
                : 1;

            $prestamo = Prestamo::create([
                'user_id' => $cliente->id,
                'solicitud_prestamo_id' => null,
                'folio' => 'PR-' . now()->year . '-' . str_pad((string) $numero, 6, '0', STR_PAD_LEFT),
                'monto_original' => $validated['monto_original'],
                'monto_total' => $resumen['total_pagar'],
                'saldo_pendiente' => $resumen['total_pagar'],
                'plazo_meses' => $validated['plazo_meses'],
                'tasa_interes' => $resumen['tasa_anual'],
                'pago_mensual' => $resumen['pago_mensual'],
                'fecha_inicio' => $fechaInicio->toDateString(),
                'fecha_final' => $fechaInicio->copy()->addMonthsNoOverflow((int) $validated['plazo_meses'])->toDateString(),
                'estado' => Estado::ACTIVO,
            ]);

            $amortizacion->guardarTabla($prestamo);

            return $prestamo;
        });

        return redirect()
            ->route('admin.prestamos.show', $prestamo->id)
            ->with('success', 'Prestamo creado correctamente para ' . $cliente->name . '.');
    }

    public function show($id)
    {
        $prestamo = Prestamo::with(['user', 'pagos', 'solicitud', 'cuotas'])
            ->where(function ($query) {
                $query->whereDoesntHave('solicitud')
                    ->orWhereHas('solicitud', fn ($solicitud) => $solicitud->where('estado', Estado::APROBADO));
            })
            ->findOrFail($id);

        return view('admin.prestamo-detalle', compact('prestamo'));
    }
}
