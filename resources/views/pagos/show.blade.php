@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('pagos.index') }}" class="text-gray-400 hover:text-gray-600">← Volver</a>
        <h1 class="text-2xl font-bold text-gray-800">Pago #{{ $pago->id }}</h1>
    </div>

    {{-- Detalle del Pago --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Detalle del Pago</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Monto Pagado</p>
                <p class="font-medium">${{ number_format($pago->monto, 2) }}</p>
            </div>
            <div>
                <p class="text-gray-500">Fecha de Pago</p>
                <p class="font-medium">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Notas</p>
                <p class="font-medium">{{ $pago->notas ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Préstamo relacionado --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-lg font-semibold text-gray-700">Préstamo Relacionado</h2>
            <a href="{{ route('prestamos.show', $pago->prestamo) }}" class="text-sm text-indigo-600 hover:underline">Ver préstamo →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Préstamo</p>
                <p class="font-medium">#{{ $pago->prestamo->id }}</p>
            </div>
            <div>
                <p class="text-gray-500">Capital</p>
                <p class="font-medium">${{ number_format($pago->prestamo->capital, 2) }}</p>
            </div>
            <div>
                <p class="text-gray-500">Plazo</p>
                <p class="font-medium">{{ $pago->prestamo->plazo_meses }} meses</p>
            </div>
            <div>
                <p class="text-gray-500">Cuota</p>
                <p class="font-medium">${{ number_format($pago->prestamo->monto_cuota, 2) }}</p>
            </div>
            <div>
                <p class="text-gray-500">Estado</p>
                <span class="px-2 py-1 rounded-full text-xs font-medium
                    @if($pago->prestamo->estado === 'activo') bg-green-100 text-green-700
                    @elseif($pago->prestamo->estado === 'en_mora') bg-red-100 text-red-700
                    @elseif($pago->prestamo->estado === 'liquidado') bg-gray-100 text-gray-600
                    @else bg-yellow-100 text-yellow-700 @endif">
                    {{ ucfirst(str_replace('_', ' ', $pago->prestamo->estado)) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Cliente --}}
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-lg font-semibold text-gray-700">Cliente</h2>
            <a href="{{ route('clientes.show', $pago->prestamo->cliente) }}" class="text-sm text-indigo-600 hover:underline">Ver cliente →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Nombre</p>
                <p class="font-medium">{{ $pago->prestamo->cliente->nombre_completo }}</p>
            </div>
            <div>
                <p class="text-gray-500">Teléfono</p>
                <p class="font-medium">{{ $pago->prestamo->cliente->telefono ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Email</p>
                <p class="font-medium">{{ $pago->prestamo->cliente->email ?? '—' }}</p>
            </div>
        </div>
    </div>

</div>
@endsection