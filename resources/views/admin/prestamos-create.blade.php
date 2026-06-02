<x-app-layout>
    <div class="p-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-purple-700">
                    Crear prestamo
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    El prestamo se crea activo y con su tabla de amortizacion.
                </p>
            </div>

            <a href="{{ route('admin.prestamos') }}"
               class="rounded border border-purple-200 px-4 py-2 text-purple-700 hover:bg-purple-50">
                Volver
            </a>
        </div>

        <form method="POST" action="{{ route('admin.prestamos.store') }}"
              class="max-w-4xl overflow-hidden rounded-xl bg-white shadow">
            @csrf

            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Cliente
                    </label>
                    <select name="user_id"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                            required>
                        <option value="">Selecciona un cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" @selected(old('user_id') == $cliente->id)>
                                {{ $cliente->name }} - {{ $cliente->email }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Monto original
                    </label>
                    <input name="monto_original"
                           type="number"
                           min="{{ $montoMinimo }}"
                           max="{{ $montoMaximo }}"
                           step="0.01"
                           value="{{ old('monto_original') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                           required>
                    <p class="mt-1 text-xs text-gray-500">
                        Rango permitido: ${{ number_format($montoMinimo, 2) }} a ${{ number_format($montoMaximo, 2) }}.
                    </p>
                    <x-input-error :messages="$errors->get('monto_original')" class="mt-2" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Plazo
                    </label>
                    <select name="plazo_meses"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                            required>
                        <option value="">Selecciona un plazo</option>
                        @foreach($plazos as $plazo)
                            <option value="{{ $plazo }}" @selected(old('plazo_meses') == $plazo)>
                                {{ $plazo }} meses
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('plazo_meses')" class="mt-2" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Fecha de inicio
                    </label>
                    <input name="fecha_inicio"
                           type="date"
                           min="{{ now()->toDateString() }}"
                           value="{{ old('fecha_inicio', now()->toDateString()) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                           required>
                    <x-input-error :messages="$errors->get('fecha_inicio')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t bg-gray-50 px-6 py-4">
                <a href="{{ route('admin.prestamos') }}"
                   class="rounded-lg border border-gray-300 px-5 py-2.5 font-semibold text-gray-700 hover:bg-white">
                    Cancelar
                </a>

                <button type="submit"
                        class="rounded-lg bg-purple-700 px-5 py-2.5 font-semibold text-white hover:bg-purple-800">
                    Crear prestamo
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
