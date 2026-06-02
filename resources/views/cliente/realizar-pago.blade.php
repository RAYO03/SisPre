<x-app-layout>
    <div class="p-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-purple-700">
                Registrar Pago
            </h1>

            <a href="{{ ($returnTo ?? null) === 'estado-cuenta' ? route('cliente.estado-cuenta', $prestamo->id) : route('cliente.prestamos') }}"
               class="rounded border border-purple-200 px-4 py-2 text-purple-700 hover:bg-purple-50">
                Volver
            </a>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-2xl bg-white p-6 shadow">
                <p class="text-sm text-gray-500">Prestamo</p>
                <h2 class="text-xl font-bold text-gray-800">{{ $prestamo->folio }}</h2>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow">
                <p class="text-sm text-gray-500">Saldo pendiente</p>
                <h2 class="text-xl font-bold text-red-600">${{ number_format($prestamo->saldo_pendiente, 2) }}</h2>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow">
                <p class="text-sm text-gray-500">Pago mensual</p>
                <h2 class="text-xl font-bold text-green-700">${{ number_format($prestamo->pago_mensual, 2) }}</h2>
            </div>
        </div>

        <div class="mb-6 overflow-hidden rounded-2xl bg-white shadow">
            <div class="border-b px-6 py-5">
                <h2 class="text-xl font-bold text-gray-800">
                    Siguiente cuota a cubrir
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    El monto sugerido puede ser mayor a la cuota mensual si existen dias de atraso e interes moratorio.
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
                    <p class="text-sm text-gray-500">Saldo de cuota</p>
                    <p class="text-lg font-bold text-gray-800">
                        ${{ number_format($resumenPago['saldo_cuota'], 2) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Dias de atraso</p>
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
                        Total sugerido = saldo de cuota + mora. Si pagas menos, la cuota quedara parcialmente pagada.
                    </p>
                    <p class="text-2xl font-bold text-purple-700">
                        ${{ number_format($resumenPago['total_sugerido'], 2) }}
                    </p>
                </div>
            </div>
        </div>

        <form method="POST"
              action="{{ route('cliente.pagos.store', $prestamo->id) }}"
              enctype="multipart/form-data"
              class="rounded-2xl bg-white p-8 shadow">
            @csrf
            <input type="hidden" name="return_to" value="{{ old('return_to', $returnTo ?? 'pagos') }}">

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Monto</label>
                    <input type="number"
                           name="monto"
                           step="0.01"
                           min="0.01"
                           max="{{ $montoMaximo }}"
                           value="{{ old('monto', $montoSugerido) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                           required>
                    <x-input-error :messages="$errors->get('monto')" class="mt-2" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Fecha de pago</label>
                    <input type="date"
                           name="fecha_pago"
                           max="{{ now()->toDateString() }}"
                           value="{{ old('fecha_pago', now()->toDateString()) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                           required>
                    <x-input-error :messages="$errors->get('fecha_pago')" class="mt-2" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Metodo de pago</label>
                    <select name="metodo_pago"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
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
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Comprobante</label>
                    <input type="file"
                           name="comprobante"
                           accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full rounded-lg border border-gray-300 p-2 text-sm file:mr-4 file:rounded file:border-0 file:bg-purple-700 file:px-4 file:py-2 file:text-white hover:file:bg-purple-800">
                    <x-input-error :messages="$errors->get('comprobante')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t pt-5">
                <a href="{{ old('return_to', $returnTo ?? 'pagos') === 'estado-cuenta' ? route('cliente.estado-cuenta', $prestamo->id) : route('cliente.pagos') }}"
                   class="rounded border border-gray-200 px-5 py-3 text-gray-600 hover:bg-gray-50">
                    Cancelar
                </a>

                <button type="submit"
                        class="rounded bg-purple-700 px-6 py-3 font-semibold text-white hover:bg-purple-800">
                    Registrar pago
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
