<x-app-layout>

    <style>
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%,100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-8px);
            }
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
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div>
                    <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                        Pago de préstamo
                    </span>

                    <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                        Registrar Pago
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Registra un pago para el préstamo {{ $prestamo->folio }}.
                    </p>
                </div>

                <a href="{{ ($returnTo ?? null) === 'estado-cuenta' ? route('cliente.estado-cuenta', $prestamo->id) : route('cliente.prestamos') }}"
                   class="rounded-2xl border border-purple-300 bg-white px-5 py-3 text-sm font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-100">
                    ← Volver
                </a>

            </div>

            {{-- RESUMEN --}}
            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">

                <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="mb-4 text-4xl animate-float">
                        📄
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Préstamo
                    </p>

                    <h2 class="mt-3 text-2xl font-black text-purple-700">
                        {{ $prestamo->folio }}
                    </h2>
                </div>

                <div class="rounded-3xl border-t-4 border-red-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="mb-4 text-4xl animate-float">
                        💰
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Saldo sin mora
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-red-600">
                        ${{ number_format($prestamo->saldo_pendiente, 2) }}
                    </h2>
                </div>

                <div class="rounded-3xl border-t-4 border-orange-500 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="mb-4 text-4xl animate-float">
                        ⚠️
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Mora pendiente
                    </p>

                    <h2 class="mt-3 text-3xl font-black {{ $moraPendiente > 0 ? 'text-red-600' : 'text-gray-800' }}">
                        ${{ number_format($moraPendiente, 2) }}
                    </h2>
                </div>

                <div class="rounded-3xl border-t-4 border-green-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="mb-4 text-4xl animate-float">
                        ✅
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Total para liquidar
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-green-600">
                        ${{ number_format($montoMaximo, 2) }}
                    </h2>
                </div>

            </div>

            {{-- SIGUIENTE CUOTA --}}
            <div class="mb-8 overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                    <div class="flex items-center gap-4">

                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-600 to-fuchsia-600 text-3xl text-white shadow-xl shadow-purple-300 animate-float">
                            📅
                        </div>

                        <div>
                            <h2 class="text-3xl font-black text-gray-800">
                                Siguiente cuota a cubrir
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                El monto sugerido puede ser mayor a la cuota mensual si existen días de atraso e interés moratorio.
                            </p>
                        </div>

                    </div>

                </div>

                <div class="grid grid-cols-1 gap-6 p-8 md:grid-cols-2 xl:grid-cols-6">

                    <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-5 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                        <p class="text-sm font-semibold text-gray-500">
                            Cuota
                        </p>

                        <p class="mt-2 text-xl font-black text-gray-800">
                            {{ $resumenPago['numero'] ? '#' . $resumenPago['numero'] : 'N/A' }}
                        </p>
                    </div>

                    <div class="rounded-3xl border-t-4 border-blue-600 bg-white p-5 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                        <p class="text-sm font-semibold text-gray-500">
                            Cuota mensual
                        </p>

                        <p class="mt-2 text-xl font-black text-gray-800">
                            ${{ number_format($resumenPago['cuota_total'], 2) }}
                        </p>
                    </div>

                    <div class="rounded-3xl border-t-4 border-green-600 bg-white p-5 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                        <p class="text-sm font-semibold text-gray-500">
                            Saldo de cuota
                        </p>

                        <p class="mt-2 text-xl font-black text-gray-800">
                            ${{ number_format($resumenPago['saldo_cuota'], 2) }}
                        </p>
                    </div>

                    <div class="rounded-3xl border-t-4 border-red-600 bg-white p-5 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                        <p class="text-sm font-semibold text-gray-500">
                            Días de atraso
                        </p>

                        <p class="mt-2 text-xl font-black {{ $resumenPago['dias_atraso'] > 0 ? 'text-red-600' : 'text-gray-800' }}">
                            {{ $resumenPago['dias_atraso'] }}
                        </p>
                    </div>

                    <div class="rounded-3xl border-t-4 border-orange-500 bg-white p-5 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                        <p class="text-sm font-semibold text-gray-500">
                            Mora
                        </p>

                        <p class="mt-2 text-xl font-black {{ $resumenPago['mora'] > 0 ? 'text-red-600' : 'text-gray-800' }}">
                            ${{ number_format($resumenPago['mora'], 2) }}
                        </p>
                    </div>

                    <div class="rounded-3xl border-t-4 border-emerald-600 bg-white p-5 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                        <p class="text-sm font-semibold text-gray-500">
                            Mora pagada
                        </p>

                        <p class="mt-2 text-xl font-black text-emerald-600">
                            ${{ number_format($resumenPago['mora_pagada'], 2) }}
                        </p>
                    </div>

                </div>

                <div class="border-t bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

                        <p class="text-sm font-semibold text-gray-600">
                            Total sugerido = saldo de cuota + mora. Si pagas menos, la cuota quedará parcialmente pagada.
                        </p>

                        <p class="text-4xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                            ${{ number_format($resumenPago['total_sugerido'], 2) }}
                        </p>

                    </div>

                </div>

            </div>

            {{-- FORMULARIO --}}
            <form method="POST"
                  action="{{ route('cliente.pagos.store', $prestamo->id) }}"
                  enctype="multipart/form-data"
                  class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                @csrf

                <input type="hidden" name="return_to" value="{{ old('return_to', $returnTo ?? 'pagos') }}">

                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                    <div class="flex items-center gap-4">

                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-green-600 to-emerald-600 text-3xl text-white shadow-xl shadow-green-300 animate-float">
                            💳
                        </div>

                        <div>
                            <h2 class="text-3xl font-black text-gray-800">
                                Datos del pago
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Captura el monto, fecha, método de pago y comprobante.
                            </p>
                        </div>

                    </div>

                </div>

                <div class="grid grid-cols-1 gap-6 p-8 md:grid-cols-2">

                    <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                        <label class="mb-3 block text-sm font-bold text-purple-700">
                            Monto
                        </label>

                        <input type="number"
                               name="monto"
                               step="0.01"
                               min="0.01"
                               max="{{ $montoMaximo }}"
                               value="{{ old('monto', $montoSugerido) }}"
                               class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                               required>

                        <p class="mt-2 text-xs text-gray-500">
                            Máximo permitido: ${{ number_format($montoMaximo, 2) }}
                        </p>

                        <x-input-error :messages="$errors->get('monto')" class="mt-2" />
                    </div>

                    <div class="rounded-3xl border-t-4 border-blue-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                        <label class="mb-3 block text-sm font-bold text-purple-700">
                            Fecha de pago
                        </label>

                        <input type="date"
                               name="fecha_pago"
                               max="{{ now()->toDateString() }}"
                               value="{{ old('fecha_pago', now()->toDateString()) }}"
                               class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                               required>

                        <x-input-error :messages="$errors->get('fecha_pago')" class="mt-2" />
                    </div>

                    <div class="rounded-3xl border-t-4 border-green-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                        <label class="mb-3 block text-sm font-bold text-purple-700">
                            Método de pago
                        </label>

                        <select name="metodo_pago"
                                class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                required>

                            @foreach($metodosPago as $metodo)
                                <option value="{{ $metodo }}" @selected(old('metodo_pago') === $metodo)>
                                    {{ $metodo }}
                                </option>
                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('metodo_pago')" class="mt-2" />
                    </div>

                    <div class="rounded-3xl border-t-4 border-fuchsia-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                        <label class="mb-3 block text-sm font-bold text-purple-700">
                            Comprobante
                        </label>

                        <input type="file"
                               name="comprobante"
                               accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full rounded-2xl border border-gray-300 p-2 text-sm shadow-sm file:mr-4 file:rounded-xl file:border-0 file:bg-gradient-to-r file:from-purple-700 file:to-fuchsia-600 file:px-4 file:py-2 file:font-bold file:text-white hover:file:from-purple-800 hover:file:to-fuchsia-700">

                        <x-input-error :messages="$errors->get('comprobante')" class="mt-2" />
                    </div>

                </div>

                <div class="flex flex-col gap-3 border-t bg-gray-50 px-8 py-6 sm:flex-row sm:justify-end">

                    <a href="{{ old('return_to', $returnTo ?? 'pagos') === 'estado-cuenta' ? route('cliente.estado-cuenta', $prestamo->id) : route('cliente.pagos') }}"
                       class="rounded-2xl border border-gray-300 bg-white px-6 py-3 text-center font-bold text-gray-600 transition hover:scale-105 hover:bg-gray-100">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-8 py-3 font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105">
                        Registrar pago
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>