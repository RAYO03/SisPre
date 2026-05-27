@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('prestamos.index') }}" class="text-gray-400 hover:text-gray-600">← Volver</a>
        <h1 class="text-2xl font-bold text-gray-800">Nuevo Préstamo</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <form method="POST" action="{{ route('prestamos.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente *</label>
                    <select name="cliente_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('cliente_id') border-red-400 @enderror">
                        <option value="">Seleccionar cliente...</option>
                        @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}"
                            {{ old('cliente_id', request('cliente_id')) == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre_completo }} — {{ $cliente->email }}
                        </option>
                        @endforeach
                    </select>
                    @error('cliente_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capital *</label>
                    <input type="number" name="capital" id="capital" step="0.01" min="100"
                           value="{{ old('capital') }}" oninput="simular()"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('capital') border-red-400 @enderror">
                    @error('capital')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tasa Anual (%) *</label>
                    <input type="number" name="tasa_anual" id="tasa_anual" step="0.01" min="0.01" max="100"
                           value="{{ old('tasa_anual') }}" oninput="simular()"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('tasa_anual') border-red-400 @enderror">
                    @error('tasa_anual')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plazo (meses) *</label>
                    <input type="number" name="plazo_meses" id="plazo_meses" min="1" max="360"
                           value="{{ old('plazo_meses') }}" oninput="simular()"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('plazo_meses') border-red-400 @enderror">
                    @error('plazo_meses')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio"
                           value="{{ old('fecha_inicio', now()->toDateString()) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('fecha_inicio') border-red-400 @enderror">
                    @error('fecha_inicio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Frecuencia</label>
                    <select name="frecuencia" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="mensual">Mensual</option>
                    </select>
                </div>

            </div>

            {{-- Simulador en tiempo real --}}
            <div id="simulador" class="hidden mt-5 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                <p class="text-sm font-semibold text-indigo-700 mb-3">📊 Simulación del Préstamo</p>
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div>
                        <p class="text-xs text-indigo-500">Cuota Mensual</p>
                        <p class="text-xl font-bold text-indigo-800" id="sim_cuota">—</p>
                    </div>  
                    <div>
                        <p class="text-xs text-indigo-500">Total a Pagar</p>
                        <p class="text-xl font-bold text-indigo-800" id="sim_total">—</p>
                    </div>
                    <div>
                        <p class="text-xs text-indigo-500">Total Intereses</p>
                        <p class="text-xl font-bold text-indigo-800" id="sim_intereses">—</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 mt-6 pt-4 border-t">
                <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">
                    Crear Préstamo
                </button>
                <a href="{{ route('prestamos.index') }}"
                   class="px-6 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function simular() {
    const capital = parseFloat(document.getElementById('capital').value);
    const tasa    = parseFloat(document.getElementById('tasa_anual').value);
    const plazo   = parseInt(document.getElementById('plazo_meses').value);
    const sim     = document.getElementById('simulador');

    if (!capital || !tasa || !plazo || capital < 100 || plazo < 1) {
        sim.classList.add('hidden');
        return;
    }

    const i       = (tasa / 100) / 12;
    const cuota   = capital * i / (1 - Math.pow(1 + i, -plazo));
    const total   = cuota * plazo;
    const interes = total - capital;
    const fmt     = n => '$' + n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    document.getElementById('sim_cuota').textContent    = fmt(cuota);
    document.getElementById('sim_total').textContent    = fmt(total);
    document.getElementById('sim_intereses').textContent = fmt(interes);
    sim.classList.remove('hidden');
}
</script>
@endsection