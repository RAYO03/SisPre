<x-app-layout>
    <style>
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeUp .8s ease-out forwards;
        }
    </style>

    <div class="min-h-screen bg-slate-100 px-4 py-10">

        <div class="mx-auto max-w-7xl animate-fade-up">

            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                        Administración de pagos
                    </span>

                    <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                        Registrar pago
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Captura pagos recibidos y aplícalos a mora, interés y capital.
                    </p>
                </div>

                <a href="{{ route('admin.pagos') }}"
                   class="rounded-2xl border border-purple-300 bg-white px-5 py-3 text-sm font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-100">
                    ← Volver
                </a>
            </div>

            @if($prestamos->isEmpty())

                <div class="rounded-3xl border border-gray-200 bg-white p-10 text-center shadow-2xl">
                    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-3xl bg-purple-100 text-4xl">
                        💳
                    </div>

                    <h2 class="text-xl font-black text-gray-800">
                        No hay préstamos disponibles
                    </h2>

                    <p class="mt-2 text-gray-500">
                        No hay préstamos activos o en mora para registrar pagos.
                    </p>
                </div>

            @else

                @if($prestamoSeleccionado)

                    <div class="mb-8 overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                        <div class="border-b border-gray-200 bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-6 py-5">
                            <h2 class="text-2xl font-black text-gray-800">
                                Seleccionar préstamo
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Elige el préstamo al que se aplicará el pago.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-6 p-6 xl:grid-cols-5">

                            <form method="GET"
                                  action="{{ route('admin.pagos.create') }}"
                                  class="xl:col-span-3">

                                <label class="mb-2 block text-sm font-semibold text-purple-700">
                                    Cliente / préstamo
                                </label>

                                <select name="prestamo_id"
                                        onchange="this.form.submit()"
                                        class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500">

                                    @foreach($prestamos as $prestamo)
                                        <option value="{{ $prestamo->id }}"
                                            @selected(optional($prestamoSeleccionado)->id === $prestamo->id)>
                                            {{ $prestamo->user->name ?? 'N/A' }} - {{ $prestamo->folio }} - Saldo ${{ number_format($prestamo->saldo_pendiente, 2) }}
                                        </option>
                                    @endforeach

                                </select>
                            </form>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 xl:col-span-2">

                                <div class="rounded-2xl border-t-4 border-purple-600 bg-white p-4 shadow-md">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        Saldo
                                    </p>

                                    <p class="mt-2 text-lg font-black text-gray-900">
                                        ${{ number_format($prestamoSeleccionado->saldo_pendiente, 2) }}
                                    </p>
                                </div>

                                <div class="rounded-2xl border-t-4 border-red-600 bg-white p-4 shadow-md">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        Mora
                                    </p>

                                    <p class="mt-2 text-lg font-black {{ $moraPendiente > 0 ? 'text-red-700' : 'text-gray-900' }}">
                                        ${{ number_format($moraPendiente, 2) }}
                                    </p>
                                </div>

                                <div class="rounded-2xl border-t-4 border-green-600 bg-green-50 p-4 shadow-md">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        Liquidar
                                    </p>

                                    <p class="mt-2 text-lg font-black text-green-700">
                                        ${{ number_format($montoMaximo, 2) }}
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>

                    <form method="POST"
                          action="{{ route('admin.pagos.store') }}"
                          enctype="multipart/form-data"
                          class="mb-8 overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                        @csrf

                        <input type="hidden"
                               name="prestamo_id"
                               value="{{ $prestamoSeleccionado->id }}">

                        <div class="border-b border-gray-200 bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-6 py-5">
                            <h2 class="text-2xl font-black text-gray-800">
                                Datos del pago
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Ingresa la información del pago recibido.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-purple-700">
                                    Monto
                                </label>

                                <input id="monto"
                                       type="number"
                                       name="monto"
                                       step="0.01"
                                       min="0.01"
                                       max="{{ $montoMaximo }}"
                                       value="{{ old('monto', number_format($montoSugerido, 2, '.', '')) }}"
                                       class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                                       required>

                                <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                    <span>
                                        Sugerido: ${{ number_format($montoSugerido, 2) }}
                                        @if($resumenPago['numero'])
                                            para cuota #{{ $resumenPago['numero'] }}
                                        @endif
                                    </span>

                                    <button type="button"
                                            onclick="document.getElementById('monto').value='{{ number_format($montoMaximo, 2, '.', '') }}'"
                                            class="font-bold text-green-700 hover:text-green-800">
                                        Liquidar ${{ number_format($montoMaximo, 2) }}
                                    </button>
                                </div>

                                <x-input-error :messages="$errors->get('monto')" class="mt-2" />
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-purple-700">
                                    Fecha de pago
                                </label>

                                <input type="date"
                                       name="fecha_pago"
                                       max="{{ now()->toDateString() }}"
                                       value="{{ old('fecha_pago', $fechaPagoSeleccionada) }}"
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

                                    @foreach($metodosPago as $metodo)
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
                                       class="w-full rounded-xl border border-gray-300 p-2 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-gradient-to-r file:from-purple-700 file:to-fuchsia-700 file:px-4 file:py-2 file:text-white">

                                <x-input-error :messages="$errors->get('comprobante')" class="mt-2" />
                            </div>

                        </div>

                        <div class="flex flex-col gap-3 border-t border-gray-200 bg-gray-50 px-6 py-5 sm:flex-row sm:justify-end">

                            <button type="submit"
                                    name="registrar_otro"
                                    value="1"
                                    class="rounded-2xl border border-purple-300 bg-white px-5 py-3 font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-100">
                                Guardar y registrar otro
                            </button>

                            <button type="submit"
                                    class="rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-6 py-3 font-bold text-white shadow-xl shadow-purple-400 transition hover:scale-105">
                                Registrar pago
                            </button>

                        </div>

                    </form>

                    <details class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">
                        <summary class="cursor-pointer border-b border-gray-200 bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-6 py-5 text-2xl font-black text-gray-800">
                            Cuotas del préstamo
                        </summary>

                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[950px] text-sm">
                                <thead class="bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 text-white shadow-lg">
                                    <tr>
                                        <th class="p-4 text-left">Cuota</th>
                                        <th class="p-4 text-center">Vence</th>
                                        <th class="p-4 text-right">Saldo cuota</th>
                                        <th class="p-4 text-right">Mora</th>
                                        <th class="p-4 text-right">Por cubrir</th>
                                        <th class="p-4 text-center">Estado</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">
                                    @forelse($cuotasResumen as $cuota)
                                        <tr class="transition hover:bg-purple-50/60">
                                            <td class="p-4 font-bold text-gray-800">
                                                #{{ $cuota['numero'] }}
                                            </td>

                                            <td class="p-4 text-center text-gray-700">
                                                {{ $cuota['fecha_vencimiento']->format('d/m/Y') }}
                                            </td>

                                            <td class="p-4 text-right text-gray-700">
                                                ${{ number_format($cuota['saldo_cuota'], 2) }}
                                            </td>

                                            <td class="p-4 text-right font-bold {{ $cuota['mora'] > 0 ? 'text-red-700' : 'text-gray-700' }}">
                                                ${{ number_format($cuota['mora'], 2) }}
                                            </td>

                                            <td class="p-4 text-right font-bold text-purple-700">
                                                ${{ number_format($cuota['por_cubrir'], 2) }}
                                            </td>

                                            <td class="p-4 text-center">
                                                <span class="rounded-full px-3 py-1 text-xs font-bold
                                                    {{ $cuota['estado_raw'] === \App\Support\Estado::PAGADA ? 'bg-green-100 text-green-700' : '' }}
                                                    {{ $cuota['estado_raw'] === \App\Support\Estado::PARCIALMENTE_PAGADA ? 'bg-blue-100 text-blue-700' : '' }}
                                                    {{ $cuota['estado_raw'] === \App\Support\Estado::VENCIDA ? 'bg-red-100 text-red-700' : '' }}
                                                    {{ in_array($cuota['estado_raw'], [\App\Support\Estado::PENDIENTE, \App\Support\Estado::ACTIVO], true) ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                                    {{ $cuota['estado'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-10 text-center text-gray-500">
                                                No hay cuotas para mostrar.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </details>

                @endif

            @endif

        </div>

    </div>
</x-app-layout>