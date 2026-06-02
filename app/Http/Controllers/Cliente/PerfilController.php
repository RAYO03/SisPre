<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        $cliente = $user->cliente;

        return view('cliente.perfil', compact('user', 'cliente'));
    }

    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        $cliente = $user->cliente;

        return view('cliente.perfil-editar', compact('user', 'cliente'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'telefono'         => ['nullable', 'string', 'digits:10'],
            'fecha_nacimiento' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:1900-01-01',
                'before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            ],
            'direccion'        => ['nullable', 'string', 'max:255'],
            'ciudad'           => ['nullable', 'string', 'max:100'],
            'estado'           => ['nullable', 'string', 'max:100'],
        ], [
            'fecha_nacimiento.required' => 'Ingresa tu fecha de nacimiento.',
            'fecha_nacimiento.date_format' => 'Ingresa una fecha de nacimiento válida.',
            'fecha_nacimiento.after_or_equal' => 'Ingresa una fecha de nacimiento válida.',
            'fecha_nacimiento.before_or_equal' => 'Debes ser mayor de edad para usar esta plataforma.',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        $user->cliente()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'telefono'         => $request->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'direccion'        => $request->direccion,
                'ciudad'           => $request->ciudad,
                'estado'           => $request->estado,
            ]
        );

        return redirect()->route('cliente.perfil')
            ->with('success', 'Perfil actualizado correctamente.');
    }
}
