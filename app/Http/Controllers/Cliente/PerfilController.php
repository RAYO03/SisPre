<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
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
            'telefono'         => [
                'required',
                'string',
                'digits:10',
                Rule::unique('clientes', 'telefono')->ignore($user->cliente?->id),
            ],
            'fecha_nacimiento' => [
                'required',
                'date_format:Y-m-d',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    try {
                        $fecha = Carbon::createFromFormat('Y-m-d', (string) $value);
                    } catch (\Throwable) {
                        $fail('Ingresa una fecha de nacimiento valida.');
                        return;
                    }

                    if (! $fecha || $fecha->format('Y-m-d') !== $value) {
                        $fail('Ingresa una fecha de nacimiento valida.');
                        return;
                    }

                    $fecha = $fecha->startOfDay();
                    $fechaMinima = Carbon::create(1900, 1, 1)->startOfDay();
                    $fechaMayorEdad = now()->subYears(18)->startOfDay();

                    if ($fecha->lt($fechaMinima)) {
                        $fail('Ingresa una fecha de nacimiento valida.');
                    }

                    if ($fecha->gt($fechaMayorEdad)) {
                        $fail('Solo se aceptan fechas de nacimiento de usuarios mayores de edad.');
                    }
                },
            ],
            'direccion'        => ['required', 'string', 'max:255'],
            'ciudad'           => ['required', 'string', 'max:100'],
            'estado'           => ['required', 'string', 'max:100'],
        ], [
            'telefono.required' => 'Ingresa tu telefono.',
            'telefono.unique' => 'Este telefono ya esta registrado.',
            'fecha_nacimiento.required' => 'Ingresa tu fecha de nacimiento.',
            'fecha_nacimiento.date_format' => 'Ingresa una fecha de nacimiento válida.',
            'fecha_nacimiento.after_or_equal' => 'Ingresa una fecha de nacimiento válida.',
            'fecha_nacimiento.before_or_equal' => 'Solo se aceptan fechas de nacimiento de usuarios mayores de edad.',
            'direccion.required' => 'Ingresa tu domicilio.',
            'ciudad.required' => 'Ingresa tu ciudad.',
            'estado.required' => 'Ingresa tu estado.',
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
