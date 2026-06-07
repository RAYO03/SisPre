<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClienteProfileIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole('cliente')) {
            return $next($request);
        }

        if ($request->routeIs('cliente.perfil', 'cliente.perfil.edit', 'cliente.perfil.update')) {
            return $next($request);
        }

        if (! $this->profileIsComplete($user->cliente)) {
            return redirect()
                ->route('cliente.perfil.edit')
                ->with('success', 'Completa tu informacion personal para continuar.');
        }

        return $next($request);
    }

    private function profileIsComplete($cliente): bool
    {
        if (! $cliente) {
            return false;
        }

        foreach (['telefono', 'fecha_nacimiento', 'direccion', 'ciudad', 'estado'] as $field) {
            if (blank($cliente->{$field})) {
                return false;
            }
        }

        return true;
    }
}
