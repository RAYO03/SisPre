<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Prestamo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::where('user_id', Auth::id())
            ->latest()
            ->get();

        $prestamosActivos = Prestamo::where('user_id', Auth::id())
            ->where('estado', 'activo')
            ->latest()
            ->get();

        return view('cliente.pagos', compact('pagos', 'prestamosActivos'));
    }

    public function create($prestamo_id)
    {
        $prestamo = Prestamo::where('user_id', Auth::id())
            ->where('estado', 'activo')
            ->findOrFail($prestamo_id);

        return view('cliente.realizar-pago', compact('prestamo'));
    }

    public function store(Request $request, $prestamo_id)
    {
        $prestamo = Prestamo::where('user_id', Auth::id())
            ->where('estado', 'activo')
            ->findOrFail($prestamo_id);

        $request->validate([
            'monto' => "required|numeric|min:1|max:{$prestamo->saldo_pendiente}",
            'metodo_pago' => 'required|in:Transferencia,Deposito,Efectivo,Tarjeta',
            'fecha_pago' => 'required|date|before_or_equal:today',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        DB::transaction(function () use ($request, $prestamo) {
            $comprobante = $request->file('comprobante')
                ? $request->file('comprobante')->store('comprobantes', 'public')
                : null;

            Pago::create([
                'prestamo_id' => $prestamo->id,
                'user_id' => Auth::id(),
                'folio_pago' => 'PG-' . date('Y') . '-' . str_pad(Pago::count() + 1, 6, '0', STR_PAD_LEFT),
                'monto' => $request->monto,
                'metodo_pago' => $request->metodo_pago,
                'fecha_pago' => $request->fecha_pago,
                'comprobante' => $comprobante,
                'estado' => 'pagado',
            ]);

            $prestamo->saldo_pendiente = max(0, $prestamo->saldo_pendiente - $request->monto);

            if ($prestamo->saldo_pendiente <= 0) {
                $prestamo->estado = 'pagado';
            }

            $prestamo->save();
        });

        return redirect()
            ->route('cliente.pagos')
            ->with('success', 'Pago registrado correctamente.');
    }
}
