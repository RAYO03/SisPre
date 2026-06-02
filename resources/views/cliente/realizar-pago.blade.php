<x-app-layout>
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .animate-slide-up {
            animation: slideUp .8s ease-out forwards;
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
    </style>

    <div class="min-h-screen bg-slate-100 px-4 py-10">

        <div class="mx-auto max-w-7xl animate-slide-up">

            {{-- ENCABEZADO --}}
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                        Gestión de pagos
                    </span>

                    <h1 class="mt-4 text-4xl font-black text-purple-700">
                        Registrar Pago
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Registra pagos y mantén actualizado el estado de tu préstamo.
                    </p>
                </div>

                <a href="{{ ($returnTo ?? null) === 'estado-cuenta' ? route('cliente.estado-cuenta', $prestamo->id) : route('cliente.prestamos') }}"
                   class="rounded-2xl border border-purple-200 bg-white px-6 py-3 text-center text-sm font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-50">
                    ← Volver
                </a>

            </div>

            {{-- RESUMEN --}}
            <div class="mb-8 grid grid-cols-1 gap-5 md:grid-cols-3">

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Préstamo</p>
                    <h2 class="mt-2 text-2xl font-black text-gray-800">
                        {{ $prestamo->folio }}
                    </h2>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Saldo pendiente</p>
                    <h2 class="mt-2 text-2xl font-black text-red-600">
                        ${{ number_format($prestamo->saldo_pendiente, 2) }}
                    </h2>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Pago mensual</p>
                    <h2 class="mt-2 text-2xl font-black text-green-700">
                        ${{ number_format($prestamo->pago_mensual, 2) }}
                    </h2>
                </div>

            </div>

            {{-- CUOTA --}}
            <div class="mb-8 overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Siguiente cuota a cubrir
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        El monto sugerido puede ser mayor a la cuota mensual si existen días de atraso e interés moratorio.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-6">

                    <div>
                        <p class="text-sm text-gray-500">Cuota</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ $resumenPago['numero'] ? '#' . $resumenPago['numero'] : 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Cuota mensual</p>
                        <p class="text-lg font-bold text-gray-800">
                            ${{ number_format($resumenPago['cuota_total'], 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Saldo cuota</p>
                        <p class="text-lg font-bold text-gray-800">
                            ${{ number_format($resumenPago['saldo_cuota'], 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Días atraso</p>
                        <p class="text-lg font-bold {{ $resumenPago['dias_atraso'] > 0 ? 'text-red-600' : 'text-gray-800' }}">
                            {{ $resumenPago['dias_atraso'] }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Mora</p>
                        <p class="text-lg font-bold {{ $resumenPago['mora'] > 0 ? 'text-red-600' : 'text-gray-800' }}">
                            ${{ number_format($resumenPago['mora'], 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Mora pagada</p>
                        <p class="text-lg font-bold text-emerald-700">
                            ${{ number_format($resumenPago['mora_pagada'], 2) }}
                        </p>
                    </div>

                </div>

                <div class="border-t bg-purple-50 px-6 py-5">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <p class="text-sm text-gray-600">
                            Total sugerido = saldo de cuota + mora.
                        </p>

                        <p class="text-3xl font-black text-purple-700">
                            ${{ number_format($resumenPago['total_sugerido'], 2) }}
                        </p>
                    </div>
                </div>

            </div>

            {{-- FORMULARIO --}}
            <form method="POST"
                  action="{{ route('cliente.pagos.store', $prestamo->id) }}"
                  enctype="multipart/form-data"
                  class="rounded-3xl border border-gray-200 bg-white p-8 shadow-2xl">

                @csrf

                <input type="hidden"
                       name="return_to"
                       value="{{ old('return_to', $returnTo ?? 'pagos') }}">

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-purple-700">
                            Monto
                        </label>

                        <input type="number"
                               name="monto"
                               step="0.01"
                               min="0.01"
                               max="{{ $montoMaximo }}"
                               value="{{ old('monto', $montoSugerido) }}"
                               class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               required>

                        <x-input-error :messages="$errors->get('monto')" class="mt-2" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-purple-700">
                            Fecha de pago
                        </label>

                        <input type="date"
                               name="fecha_pago"
                               max="{{ now()->toDateString() }}"
                               value="{{ old('fecha_pago', now()->toDateString()) }}"
                               class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               required>

                        <x-input-error :messages="$errors->get('fecha_pago')" class="mt-2" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-purple-700">
                            Método de pago
                        </label>

                        <select name="metodo_pago"
                                class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                                required>

                            @foreach(['Transferencia', 'Deposito', 'Efectivo', 'Tarjeta'] as $metodo)
                                <option value="{{ $metodo }}" @selected(old('metodo_pago') === $metodo)>
                                    {{ $metodo }}
                                </option>
                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('metodo_pago')" class="mt-2" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-purple-700">
                            Comprobante
                        </label>

                        <input type="file"
                               name="comprobante"
                               accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full rounded-xl border border-gray-300 p-2 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-purple-700 file:px-4 file:py-2 file:text-white hover:file:bg-purple-800">

                        <x-input-error :messages="$errors->get('comprobante')" class="mt-2" />
                    </div>

                </div>

                <div class="mt-8 flex flex-col gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">

                    <a href="{{ old('return_to', $returnTo ?? 'pagos') === 'estado-cuenta' ? route('cliente.estado-cuenta', $prestamo->id) : route('cliente.pagos') }}"
                       class="rounded-2xl border border-gray-200 bg-white px-5 py-3 text-center font-semibold text-gray-600 transition hover:bg-gray-100">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="rounded-2xl bg-gradient-to-r from-purple-600 to-fuchsia-600 px-6 py-3 font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105 hover:from-purple-700 hover:to-fuchsia-700">
                        Registrar pago
                    </button>

                </div>

            </form>

        </div>

    </div>
</x-app-layout>