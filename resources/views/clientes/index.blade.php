@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Clientes</h1>
    <a href="{{ route('clientes.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">
        + Nuevo Cliente
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Nombre</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Email</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Teléfono</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Préstamos</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($clientes as $cliente)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $cliente->nombre_completo }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $cliente->email }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $cliente->telefono ?? '—' }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full text-xs font-medium">
                        {{ $cliente->prestamos_count }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex justify-center gap-3">
                        <a href="{{ route('clientes.show', $cliente) }}" class="text-indigo-600 hover:underline">Ver</a>
                        <a href="{{ route('clientes.edit', $cliente) }}" class="text-gray-500 hover:underline">Editar</a>
                        <form method="POST" action="{{ route('clientes.destroy', $cliente) }}"
                              onsubmit="return confirm('¿Eliminar este cliente?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-10 text-center text-gray-400">
                    No hay clientes registrados aún.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $clientes->links() }}
</div>
@endsection