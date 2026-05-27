@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('clientes.show', $cliente) }}" class="text-gray-400 hover:text-gray-600">← Volver</a>
        <h1 class="text-2xl font-bold text-gray-800">Editar Cliente</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <form method="POST" action="{{ route('clientes.update', $cliente) }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('nombre') border-red-400 @enderror">
                    @error('nombre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Apellido *</label>
                    <input type="text" name="apellido" value="{{ old('apellido', $cliente->apellido) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('apellido') border-red-400 @enderror">
                    @error('apellido')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $cliente->email) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $cliente->direccion) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ingresos Mensuales</label>
                    <input type="number" name="ingresos_mensuales" step="0.01"
                           value="{{ old('ingresos_mensuales', $cliente->ingresos_mensuales) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Referencia</label>
                    <input type="text" name="referencia_nombre"
                           value="{{ old('referencia_nombre', $cliente->referencia_nombre) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono Referencia</label>
                    <input type="text" name="referencia_telefono"
                           value="{{ old('referencia_telefono', $cliente->referencia_telefono) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>

            </div>

            <div class="flex gap-3 mt-6 pt-4 border-t">
                <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">
                    Actualizar Cliente
                </button>
                <a href="{{ route('clientes.show', $cliente) }}"
                   class="px-6 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection