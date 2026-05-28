<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Cliente;
use App\Http\Requests\PrestamoRequest;
use App\Services\AmortizacionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PrestamoController extends Controller
{
    public function __construct(
        private AmortizacionService $amortizacion
    ) {}

    public function index()
    {
        $prestamos = Prestamo::with('user')
            ->latest()
            ->paginate(15);

        return view('prestamos.index', compact('prestamos'));
    }

    public function create()
    {
        if (Auth::user()->role === 'usuario') {
            return view('usuario.prestamos.create');
        }

        $clientes = Cliente::orderBy('nombre')->get();

        return view('prestamos.create', compact('clientes'));
    }

    public function store(PrestamoRequest $request)
    {
        DB::transaction(function () use ($request) {

            $data = $request->validated();

            $data['user_id'] = Auth::id();

            $data['monto_cuota'] = $this->amortizacion->calcularCuotaFija(
                $data['capital'],
                $data['tasa_anual'],
                $data['plazo_meses']
            );

            $data['estado'] = 'solicitado';

            $prestamo = Prestamo::create($data);

            $this->amortizacion->guardarTabla($prestamo);
        });

        if (Auth::user()->role === 'usuario') {

            return redirect()
                ->route('usuario.prestamos')
                ->with('success', 'Tu solicitud de préstamo fue enviada.');
        }

        return redirect()
            ->route('prestamos.index')
            ->with('success', 'Préstamo creado y tabla generada.');
    }

    public function show(Prestamo $prestamo)
    {
        if (
            Auth::user()->role === 'usuario' &&
            $prestamo->user_id !== Auth::id()
        ) {
            abort(403, 'No tienes permiso para ver este préstamo.');
        }

        $prestamo->load([
            'user',
            'cuotas',
            'pagos'
        ]);

        return view('prestamos.show', compact('prestamo'));
    }

    public function misPrestamos()
    {
        $prestamos = Prestamo::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('usuario.prestamos.index', compact('prestamos'));
    }

    public function aprobar(Prestamo $prestamo)
    {
        abort_if(
            $prestamo->estado !== 'solicitado',
            403
        );

        $prestamo->update([
            'estado' => 'aprobado'
        ]);

        return back()->with(
            'success',
            'Préstamo aprobado.'
        );
    }

    public function activar(Prestamo $prestamo)
    {
        abort_if(
            $prestamo->estado !== 'aprobado',
            403
        );

        $prestamo->update([
            'estado' => 'activo'
        ]);

        return back()->with(
            'success',
            'Préstamo activado.'
        );
    }
}