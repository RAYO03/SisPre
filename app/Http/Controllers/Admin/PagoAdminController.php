<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Support\Estado;


class PagoAdminController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['user', 'prestamo'])->latest()->get();

        return view('admin.pagos', compact('pagos'));
    }

    public function create()
    {
        $prestamos = Prestamo::with('user')
            ->where('estado', Estado::ACTIVO)
            ->get();

        return view('admin.registrar-pago', compact('prestamos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prestamo_id' => 'required|exists:prestamos,id',
            'monto' => 'required|numeric|min:1',
            'metodo_pago' => 'required|string|max:255',
        ]);

        $prestamo = Prestamo::findOrFail($request->prestamo_id);

        Pago::create([
            'prestamo_id' => $prestamo->id,
            'user_id' => $prestamo->user_id,
            'folio_pago' => 'PG-' . date('Y') . '-' . str_pad(Pago::count() + 1, 6, '0', STR_PAD_LEFT),
            'monto' => $request->monto,
            'metodo_pago' => $request->metodo_pago,
            'fecha_pago' => now(),
            'estado' => Estado::LIQUIDADO,
        ]);

        $prestamo->saldo_pendiente -= $request->monto;

        if ($prestamo->saldo_pendiente <= 0) {
            $prestamo->saldo_pendiente = 0;
            $prestamo->estado = Estado::LIQUIDADO;
        }

        $prestamo->save();

        return redirect()
            ->route('admin.pagos')
            ->with('success', 'Pago registrado correctamente.');
    }
}
