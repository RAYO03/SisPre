<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;
use App\Models\User;
use App\Services\AmortizacionService;
use App\Support\Estado;
use App\Support\PrestamoConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PrestamoAdminController extends Controller
{
    public function index()
    {
        $prestamos = Prestamo::with(['user', 'solicitud'])
            ->whereIn('estado', [
                Estado::ACTIVO,
                Estado::EN_MORA,
                Estado::LIQUIDADO,
            ])
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
        $montoMinimo = PrestamoConfig::MONTO_MINIMO;
        $montoMaximo = PrestamoConfig::MONTO_MAXIMO;

        return view('admin.prestamos-create', compact('clientes', 'plazos', 'montoMinimo', 'montoMaximo'));
    }

    public function store(Request $request, AmortizacionService $amortizacion)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'monto_original' => ['required', 'numeric', 'min:' . PrestamoConfig::MONTO_MINIMO, 'max:' . PrestamoConfig::MONTO_MAXIMO],
            'plazo_meses' => ['required', 'integer', Rule::in($amortizacion->plazosPermitidos())],
            'fecha_inicio' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $cliente = User::role('cliente')->findOrFail($validated['user_id']);

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

    public function edit($id, AmortizacionService $amortizacion)
    {
        $prestamo = Prestamo::with(['user', 'solicitud'])
            ->where(function ($query) {
                $query->whereDoesntHave('solicitud')
                    ->orWhereHas('solicitud', fn ($solicitud) => $solicitud->where('estado', Estado::APROBADO));
            })
            ->findOrFail($id);

        $this->abortIfLiquidado($prestamo);

        $clientes = User::role('cliente')
            ->orderBy('name')
            ->get();

        $plazos = $amortizacion->plazosPermitidos();
        $montoMinimo = PrestamoConfig::MONTO_MINIMO;
        $montoMaximo = PrestamoConfig::MONTO_MAXIMO;

        return view('admin.prestamo-editar', compact(
            'prestamo',
            'clientes',
            'plazos',
            'montoMinimo',
            'montoMaximo'
        ));
    }

    public function update(Request $request, $id, AmortizacionService $amortizacion)
    {
        $prestamo = Prestamo::with(['pagos', 'cuotas'])
            ->where(function ($query) {
                $query->whereDoesntHave('solicitud')
                    ->orWhereHas('solicitud', fn ($solicitud) => $solicitud->where('estado', Estado::APROBADO));
            })
            ->findOrFail($id);

        $this->abortIfLiquidado($prestamo);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'monto_original' => ['required', 'numeric', 'min:' . PrestamoConfig::MONTO_MINIMO, 'max:' . PrestamoConfig::MONTO_MAXIMO],
            'plazo_meses' => ['required', 'integer', Rule::in($amortizacion->plazosPermitidos())],
            'fecha_inicio' => ['required', 'date'],
            'estado' => ['required', Rule::in([
                Estado::ACTIVO,
                Estado::EN_MORA,
                Estado::LIQUIDADO,
            ])],
        ]);

        $cliente = User::role('cliente')->findOrFail($validated['user_id']);

        $fechaInicio = Carbon::parse($validated['fecha_inicio']);

        $resumen = $amortizacion->generarResumen(
            (float) $validated['monto_original'],
            (int) $validated['plazo_meses'],
            $fechaInicio
        );

        DB::transaction(function () use ($prestamo, $cliente, $validated, $fechaInicio, $resumen, $amortizacion) {
            $prestamo->update([
                'user_id' => $cliente->id,
                'monto_original' => $validated['monto_original'],
                'monto_total' => $resumen['total_pagar'],
                'saldo_pendiente' => $resumen['total_pagar'],
                'plazo_meses' => $validated['plazo_meses'],
                'tasa_interes' => $resumen['tasa_anual'],
                'pago_mensual' => $resumen['pago_mensual'],
                'fecha_inicio' => $fechaInicio->toDateString(),
                'fecha_final' => $fechaInicio->copy()->addMonthsNoOverflow((int) $validated['plazo_meses'])->toDateString(),
                'estado' => $validated['estado'],
            ]);

            $prestamo->cuotas()->delete();

            $amortizacion->guardarTabla($prestamo);
        });

        return redirect()
            ->route('admin.prestamos.show', $prestamo->id)
            ->with('success', 'Préstamo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $prestamo = Prestamo::with(['pagos', 'cuotas'])
            ->where(function ($query) {
                $query->whereDoesntHave('solicitud')
                    ->orWhereHas('solicitud', fn ($solicitud) => $solicitud->where('estado', Estado::APROBADO));
            })
            ->findOrFail($id);

        $this->abortIfLiquidado($prestamo);

        DB::transaction(function () use ($prestamo) {
            $prestamo->pagos()->delete();
            $prestamo->cuotas()->delete();
            $prestamo->delete();
        });

        return redirect()
            ->route('admin.prestamos')
            ->with('success', 'Préstamo eliminado correctamente.');
    }

    private function abortIfLiquidado(Prestamo $prestamo): void
    {
        abort_if(
            $prestamo->estado === Estado::LIQUIDADO,
            403,
            'Los prestamos liquidados solo pueden consultarse.'
        );
    }
}
