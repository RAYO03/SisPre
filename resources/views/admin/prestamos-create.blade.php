<x-app-layout>
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-slide-up {
            animation: slideUp .8s ease-out forwards;
        }
    </style>

    <div class="min-h-screen bg-slate-100 px-4 py-10">

        <div class="mx-auto max-w-5xl animate-slide-up">

            {{-- ENCABEZADO --}}
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div>
                    <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                        Administración de préstamos
                    </span>

                    <h1 class="mt-4 text-4xl font-black text-purple-700">
                        Crear Préstamo
                    </h1>

                    <p class="mt-2 text-gray-500">
                        El préstamo se creará activo y generará automáticamente su tabla de amortización.
                    </p>
                </div>

                <a href="{{ route('admin.prestamos') }}"
                   class="rounded-2xl border border-purple-200 bg-white px-5 py-3 text-sm font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-50">
                    ← Volver
                </a>

            </div>

            {{-- FORMULARIO --}}
            <form method="POST"
                  action="{{ route('admin.prestamos.store') }}"
                  class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                @csrf

                {{-- HEADER --}}
                <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Información del préstamo
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Completa los datos para generar el préstamo del cliente.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

                    {{-- CLIENTE --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-purple-700">
                            Cliente
                        </label>

                        <select name="user_id"
                                class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                                required>

                            <option value="">Selecciona un cliente</option>

                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}"
                                    @selected(old('user_id') == $cliente->id)>
                                    {{ $cliente->name }} - {{ $cliente->email }}
                                </option>
                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                    </div>

                    {{-- MONTO --}}
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-purple-700">
                            Monto original
                        </label>

                        <input name="monto_original"
                               type="number"
                               min="{{ $montoMinimo }}"
                               max="{{ $montoMaximo }}"
                               step="0.01"
                               value="{{ old('monto_original') }}"
                               class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               required>

                        <p class="mt-1 text-xs text-gray-500">
                            Rango permitido: ${{ number_format($montoMinimo, 2) }} a ${{ number_format($montoMaximo, 2) }}.
                        </p>

                        <x-input-error :messages="$errors->get('monto_original')" class="mt-2" />
                    </div>

                    {{-- PLAZO --}}
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-purple-700">
                            Plazo
                        </label>

                        <select name="plazo_meses"
                                class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                                required>

                            <option value="">Selecciona un plazo</option>

                            @foreach($plazos as $plazo)
                                <option value="{{ $plazo }}"
                                    @selected(old('plazo_meses') == $plazo)>
                                    {{ $plazo }} meses
                                </option>
                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('plazo_meses')" class="mt-2" />
                    </div>

                    {{-- FECHA --}}
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-purple-700">
                            Fecha de inicio
                        </label>

                        <input name="fecha_inicio"
                               type="date"
                               min="{{ now()->toDateString() }}"
                               value="{{ old('fecha_inicio', now()->toDateString()) }}"
                               class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               required>

                        <x-input-error :messages="$errors->get('fecha_inicio')" class="mt-2" />
                    </div>

                    {{-- RESUMEN --}}
                    <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5">
                        <h3 class="mb-2 text-sm font-bold text-purple-700">
                            Información
                        </h3>

                        <p class="text-sm text-gray-600">
                            Al crear el préstamo se generarán automáticamente:
                        </p>

                        <ul class="mt-3 space-y-1 text-sm text-gray-700">
                            <li>✔ Tabla de amortización</li>
                            <li>✔ Cuotas mensuales</li>
                            <li>✔ Estado activo</li>
                            <li>✔ Folio automático</li>
                        </ul>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="flex flex-col gap-3 border-t border-gray-200 bg-gray-50 px-6 py-5 sm:flex-row sm:justify-end">

                    <a href="{{ route('admin.prestamos') }}"
                       class="rounded-2xl border border-gray-300 bg-white px-5 py-3 text-center font-semibold text-gray-700 transition hover:bg-gray-100">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="rounded-2xl bg-gradient-to-r from-purple-600 to-fuchsia-600 px-6 py-3 font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105 hover:from-purple-700 hover:to-fuchsia-700">
                        Crear préstamo
                    </button>

                </div>

            </form>

        </div>

    </div>
</x-app-layout>
