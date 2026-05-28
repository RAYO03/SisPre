<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Prestamo;
use App\Http\Requests\PagoRequest;
use App\Services\PagoService;
use Carbon\Carbon;

class PagoController extends Controller
{
    public function __construct(private PagoService $pagoService) {}

    public function index()
    {
        $pagos = Pago::with('prestamo.cliente')
            ->latest()
            ->paginate(15);

        return view('pagos.index', compact('pagos'));
    }

    public function create(Prestamo $prestamo)
    {
        abort_if(!in_array($prestamo->estado, ['activo', 'en_mora']), 403);

        $prestamo->load(['cuotas', 'cliente']);

        return view('pagos.create', compact('prestamo'));
    }

    public function store(PagoRequest $request, Prestamo $prestamo)
    {
        abort_if(!in_array($prestamo->estado, ['activo', 'en_mora']), 403);

        $this->pagoService->registrarPago(
            $prestamo,
            (float) $request->validated('monto'),
            Carbon::parse($request->validated('fecha_pago')),
            $request->validated('notas', '')
        );

        return redirect()
            ->route('prestamos.show', $prestamo)
            ->with('success', 'Pago registrado correctamente.');
    }
}