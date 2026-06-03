<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ClienteAdminController extends Controller
{
    public function index()
    {
        $clientes = User::with('cliente')
            ->role('cliente')
            ->latest()
            ->get();

        return view('admin.clientes', compact('clientes'));
    }

    public function show($id)
    {
        $user = User::with([
            'cliente',
            'solicitudes',
            'prestamos',
            'pagos'
        ])
            ->role('cliente')
            ->findOrFail($id);

        return view('admin.cliente-detalle', [
            'user' => $user,
            'cliente' => $user->cliente,
        ]);
    }

    public function edit($id)
    {
        $user = User::with('cliente')
            ->role('cliente')
            ->findOrFail($id);

        return view('admin.cliente-editar', [
            'user' => $user,
            'cliente' => $user->cliente,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::with('cliente')
            ->role('cliente')
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($user->cliente) {
            $user->cliente->update([
                'telefono' => $request->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'direccion' => $request->direccion,
                'ciudad' => $request->ciudad,
                'estado' => $request->estado,
            ]);
        }

        return redirect()
            ->route('admin.clientes.show', $user->id)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy($id)
    {
        $user = User::with('cliente')
            ->role('cliente')
            ->findOrFail($id);

        if ($user->cliente) {
            $user->cliente->delete();
        }

        $user->delete();

        return redirect()
            ->route('admin.clientes')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
