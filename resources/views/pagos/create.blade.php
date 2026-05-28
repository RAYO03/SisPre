@extends('layouts.app')

@section('content')
    <div class="max-w-lg mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('prestamos.show', $prestamo) }}" class="text-gray-400 hover:text-gray-600">← Volver</a>
            <h1 class="text-2xl font-bold text-gray-800">Registrar Pago</h1>
        </div>

        {{-- Resumen del préstamo --}}
        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-6">
            <p class="text-sm font-semibold text-indigo-700 mb-2">Préstamo #{{ $prestamo->id }}</p>
            <p class="text-sm text-indigo-600">Cliente: <strong>{{ $prestamo->cliente->nombre_completo }}</strong></p>
            <p class="text-sm text-indigo-600">Cuota mensual: <strong>${{ number_format($prestamo->monto_cuota, 2) }}</strong>
            </p>
            @php
                $pendientes = $prestamo->cuotas->whereIn('estado', ['pendiente', 'vencida', 'parcialmente_pagada']);
                $proximaVencida = $pendientes->sortBy('numero')->first();
            @endphp
            @if ($proximaVencida)
                <p class="text-sm text-indigo-600 mt-1">
                    Próxima cuota: <strong>#{{ $proximaVencida->numero }}</strong>
                    — vence {{ $proximaVencida->fecha_vencimiento->format('d/m/Y') }}
                    — pendiente:
                    <strong>${{ number_format($proximaVencida->saldo_pendiente, 2) }}</strong>
                </p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
            <form method="POST" action="{{ route('pagos.store', $prestamo) }}">

                @csrf

                <div class="space-y-4">


                    @php
                        $cuotaActiva = $prestamo->cuotas
                            ->whereIn('estado', ['pendiente', 'parcialmente_pagada'])
                            ->sortBy('numero')
                            ->first();

                        $saldoPendiente = $cuotaActiva ? $cuotaActiva->saldo_pendiente : 0;
                        $saldoPendienteInput = number_format($saldoPendiente, 2, '.', '');
                    @endphp

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Monto del Pago *</label>
                        <input type="number" name="monto" step="0.01" min="0.01" max="{{ $saldoPendienteInput }}" required
                            value="{{ old('monto', $saldoPendienteInput) }}"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('monto') border-red-400 @enderror">

                        <p class="text-xs text-gray-500 mt-1">Saldo pendiente: ${{ number_format($saldoPendiente, 2) }}</p>

                        @error('monto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha del Pago *</label>
                        <input type="date" name="fecha_pago" value="{{ old('fecha_pago', now()->toDateString()) }}" required
                            max="{{ now()->toDateString() }}"

                        
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('fecha_pago') border-red-400 @enderror">
                        @error('fecha_pago')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas (opcional)</label>
                        <textarea name="notas" rows="3" maxlength="500"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                            placeholder="Ej: Pago en efectivo, transferencia #123...">{{ old('notas') }}</textarea>
                    </div>

                </div>

                <div class="flex gap-3 mt-6 pt-4 border-t">
                    <button type="submit"
                        class="flex-1 bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">
                        💵 Registrar Pago
                    </button>
                    <a href="{{ route('prestamos.show', $prestamo) }}"
                        class="flex-1 text-center border py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
