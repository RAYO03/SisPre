@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('clientes.index') }}" class="text-gray-400 hover:text-gray-600">← Volver</a>
        <h1 class="text-2xl font-bold text-gray-800">{{ $cliente->nombre_completo }}</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-lg font-semibold text-gray-700">Datos Personales</h2>
            <a href="{{ route('clientes.edit', $cliente) }}" class="text-sm text-indigo-600 hover:underline">Editar</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Email</p>
                <p class="font-medium">{{ $cliente->email }}</p>
            </div>
            <div>
                <p class="text-gray-500">Teléfono</p>
                <p class="font-medium">{{ $cliente->telefono ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Dirección</p>
                <p class="font-medium">{{ $cliente->direccion ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Ingresos Mensuales</p>
                <p class="font-medium">
                    {{ $cliente->ingresos_mensuales ? '$'.number_format($cliente->ingresos_mensuales,2) : '—' }}
                </p>
            </div>
            <div>
                <p class="text-gray-500">Referencia</p>
                <p class="font-medium">{{ $cliente->referencia_nombre ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tel. Referencia</p>
                <p class="font-medium">{{ $cliente->referencia_telefono ?? '—' }}</p>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-3">
        <h2 class="text-lg font-semibold text-gray-700">Préstamos</h2>
        <a href="{{ route('prestamos.create') }}?cliente_id={{ $cliente->id }}"
           class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 text-sm">
            + Nuevo Préstamo
        </a>
    </div>

    @forelse($cliente->prestamos as $prestamo)
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-3 flex justify-between items-center">
        <div>
            <p class="font-medium text-gray-800">Préstamo #{{ $prestamo->id }}</p>
            <p class="text-sm text-gray-500">
                Capital: ${{ number_format($prestamo->capital,2) }} •
                {{ $prestamo->plazo_meses }} meses •
                Cuota: ${{ number_format($prestamo->monto_cuota,2) }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-2 py-1 rounded-full text-xs font-medium
                @if($prestamo->estado==='activo') bg-green-100 text-green-700
                @elseif($prestamo->estado==='en_mora') bg-red-100 text-red-700
                @elseif($prestamo->estado==='liquidado') bg-gray-100 text-gray-600
                @else bg-yellow-100 text-yellow-700 @endif">
                {{ ucfirst(str_replace('_',' ',$prestamo->estado)) }}
            </span>
            <a href="{{ route('prestamos.show', $prestamo) }}" class="text-sm text-indigo-600 hover:underline">Ver →</a>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border p-8 text-center text-gray-400">
        Este cliente no tiene préstamos aún.
    </div>
    @endforelse

</div>
@endsection