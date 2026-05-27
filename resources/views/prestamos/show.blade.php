@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="flex items-start justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href__="{{ route('prestamos.index') }}" class="text-gray-400 hover:text-gray-600">← Volver</a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Préstamo #{{ $prestamo->id }}</h1>
                <a href__="{{ route('clientes.show', $prestamo->cliente) }}"
                   class="text-sm text-indigo-600 hover:underline">
                    {{ $prestamo->cliente->nombre_completo }}
                </a>
            </div>
        </div>
        <span class="px-3 py-1 rounded-full text-sm font-medium
            @if($prestamo->estado==='activo') bg-green-100 text-green-700
            @elseif($prestamo->estado==='en_mora') bg-red-100 text-red-700
            @elseif($prestamo->estado==='liquidado') bg-gray-100 text-gray-600
            @elseif($prestamo->estado==='aprobado') bg-blue-100 text-blue-700
            @else bg-yellow-100 text-yellow-700 @endif">
            {{ ucfirst(str_replace('_',' ',$prestamo->estado)) }}
        </span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500">Capital</p>
            <p class="text-xl font-bold text-gray-800">${{ number_format($prestamo->capital,2) }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500">Tasa Anual</p>
            <p class="text-xl font-bold text-gray-800">{{ $prestamo->tasa_anual }}%</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500">Plazo</p>
            <p class="text-xl font-bold text-gray-800">{{ $prestamo->plazo_meses }} meses</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500">Cuota Mensual</p>
            <p class="text-xl font-bold text-indigo-700">${{ number_format($prestamo->monto_cuota,2) }}</p>
        </div>
    </div>

    <div class="flex gap-3 mb-8">
        @if($prestamo->estado==='solicitado')
        <form method="POST" action="{{ route('prestamos.aprobar', $prestamo) }}">
            @csrf @method('PATCH')
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                ✅ Aprobar Préstamo
            </button>
        </form>
        @endif
        @if($prestamo->estado==='aprobado')
        <form method="POST" action="{{ route('prestamos.activar', $prestamo) }}">
            @csrf @method('PATCH')
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                🚀 Activar Préstamo
            </button>
        </form>
        @endif
        @if(in_array($prestamo->estado, ['activo','en_mora']))
        <a href__="{{ route('pagos.create', $prestamo) }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
            💵 Registrar Pago
        </a>
        @endif
    </div>

    <h2 class="text-lg font-semibold text-gray-700 mb-3">Tabla de Amortización</h2>
    <div class="bg-white rounded-xl shadow-sm border overflow-x-auto mb-8">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-3 py-3 text-left text-gray-600">#</th>
                    <th class="px-3 py-3 text-left text-gray-600">Vencimiento</th>
                    <th class="px-3 py-3 text-right text-gray-600">Capital</th>
                    <th class="px-3 py-3 text-right text-gray-600">Interés</th>
                    <th class="px-3 py-3 text-right text-gray-600">Cuota</th>
                    <th class="px-3 py-3 text-right text-gray-600">Pagado</th>
                    <th class="px-3 py-3 text-right text-gray-600">Saldo</th>
                    <th class="px-3 py-3 text-center text-gray-600">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($prestamo->cuotas as $cuota)
                <tr class="hover:opacity-90
                    @if($cuota->estado==='pagada') bg-green-50
                    @elseif($cuota->estado==='vencida') bg-red-50
                    @elseif($cuota->estado==='parcialmente_pagada') bg-yellow-50
                    @endif">
                    <td class="px-3 py-2.5 text-gray-400">{{ $cuota->numero }}</td>
                    <td class="px-3 py-2.5">{{ $cuota->fecha_vencimiento->format('d/m/Y') }}</td>
                    <td class="px-3 py-2.5 text-right">${{ number_format($cuota->capital,2) }}</td>
                    <td class="px-3 py-2.5 text-right">${{ number_format($cuota->interes,2) }}</td>
                    <td class="px-3 py-2.5 text-right font-medium">${{ number_format($cuota->cuota_total,2) }}</td>
                    <td class="px-3 py-2.5 text-right">${{ number_format($cuota->monto_pagado,2) }}</td>
                    <td class="px-3 py-2.5 text-right">${{ number_format($cuota->saldo_restante,2) }}</td>
                    <td class="px-3 py-2.5 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs
                            @if($cuota->estado==='pagada') bg-green-100 text-green-700
                            @elseif($cuota->estado==='vencida') bg-red-100 text-red-700
                            @elseif($cuota->estado==='parcialmente_pagada') bg-yellow-100 text-yellow-700
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ ucfirst(str_replace('_',' ',$cuota->estado)) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 class="text-lg font-semibold text-gray-700 mb-3">Historial de Pagos</h2>
    @forelse($prestamo->pagos->sortByDesc('fecha_pago') as $pago)
    <div class="bg-white border rounded-xl p-4 mb-3 flex justify-between items-center">
        <div>
            <p class="font-bold text-gray-800 text-lg">${{ number_format($pago->monto,2) }}</p>
            <p class="text-sm text-gray-500">{{ $pago->fecha_pago->format('d/m/Y') }}</p>
            @if($pago->notas)
                <p class="text-xs text-gray-400 mt-1">{{ $pago->notas }}</p>
            @endif
        </div>
        <div class="text-right text-sm space-y-1">
            @if($pago->interes_moratorio_pagado > 0)
                <p class="text-red-600">Moratorio: ${{ number_format($pago->interes_moratorio_pagado,2) }}</p>
            @endif
            <p class="text-orange-600">Interés: ${{ number_format($pago->interes_ordinario_pagado,2) }}</p>
            <p class="text-indigo-600">Capital: ${{ number_format($pago->capital_pagado,2) }}</p>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border p-8 text-center text-gray-400">
        No hay pagos registrados aún.
    </div>
    @endforelse

</div>
@endsection