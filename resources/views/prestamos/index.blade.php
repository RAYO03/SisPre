@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Préstamos</h1>
    <a href__="{{ route('prestamos.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">
        + Nuevo Préstamo
    </a>
</div>

@php
    $todos      = $prestamos->getCollection();
    $activos    = $todos->where('estado','activo')->count();
    $enMora     = $todos->where('estado','en_mora')->count();
    $liquidados = $todos->where('estado','liquidado')->count();
@endphp

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border p-4 text-center">
        <p class="text-2xl font-bold text-gray-800">{{ $prestamos->total() }}</p>
        <p class="text-xs text-gray-500 mt-1">Total</p>
    </div>
    <div class="bg-green-50 rounded-xl border border-green-100 p-4 text-center">
        <p class="text-2xl font-bold text-green-700">{{ $activos }}</p>
        <p class="text-xs text-green-600 mt-1">Activos</p>
    </div>
    <div class="bg-red-50 rounded-xl border border-red-100 p-4 text-center">
        <p class="text-2xl font-bold text-red-700">{{ $enMora }}</p>
        <p class="text-xs text-red-600 mt-1">En Mora</p>
    </div>
    <div class="bg-gray-50 rounded-xl border p-4 text-center">
        <p class="text-2xl font-bold text-gray-600">{{ $liquidados }}</p>
        <p class="text-xs text-gray-500 mt-1">Liquidados</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">#</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Cliente</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Capital</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Cuota</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Plazo</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Inicio</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Estado</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($prestamos as $prestamo)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-400">{{ $prestamo->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $prestamo->cliente->nombre_completo }}</td>
                <td class="px-4 py-3 text-right">${{ number_format($prestamo->capital,2) }}</td>
                <td class="px-4 py-3 text-right">${{ number_format($prestamo->monto_cuota,2) }}</td>
                <td class="px-4 py-3 text-center">{{ $prestamo->plazo_meses }}m</td>
                <td class="px-4 py-3">{{ $prestamo->fecha_inicio->format('d/m/Y') }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                        @if($prestamo->estado==='activo') bg-green-100 text-green-700
                        @elseif($prestamo->estado==='en_mora') bg-red-100 text-red-700
                        @elseif($prestamo->estado==='liquidado') bg-gray-100 text-gray-600
                        @elseif($prestamo->estado==='aprobado') bg-blue-100 text-blue-700
                        @else bg-yellow-100 text-yellow-700 @endif">
                        {{ ucfirst(str_replace('_',' ',$prestamo->estado)) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <a href__="{{ route('prestamos.show', $prestamo) }}" class="text-indigo-600 hover:underline">Ver</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-10 text-center text-gray-400">No hay préstamos registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $prestamos->links() }}</div>
@endsection