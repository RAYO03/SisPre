<x-app-layout>
    <div class="space-y-6 p-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-purple-700">Registrar pago</h1>
                <p class="mt-1 text-sm text-gray-500">Captura pagos recibidos y aplicalos a mora, interes y capital.</p>
            </div>

            <a href="{{ route('admin.pagos') }}"
               class="rounded border border-purple-200 px-4 py-2 text-purple-700 hover:bg-purple-50">
                Volver
            </a>
        </div>

        @if($prestamos->isEmpty())
            <div class="rounded-xl bg-white p-8 text-center shadow">
                <p class="text-gray-500">No hay prestamos activos o en mora para registrar pagos.</p>
            </div>
        @else
            <form method="GET" action="{{ route('admin.pagos.create') }}" class="rounded-xl bg-white p-5 shadow">
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Cliente / prestamo</label>
                        <select name="prestamo_id"
                                onchange="this.form.submit()"
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            @foreach($prestamos as $prestamo)
                                <option value="{{ $prestamo->id }}" @selected(optional($prestamoSeleccionado)->id === $prestamo->id)>
                                    {{ $prestamo->user->name ?? 'N/A' }} - {{ $prestamo->folio }} - Saldo ${{ number_format($prestamo->saldo_pendiente, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Fecha para calcular mora</label>
                        <input type="date"
                               name="fecha_pago"
                               max="{{ now()->toDateString() }}"
                               value="{{ $fechaPagoSeleccionada }}"
                               onchange="this.form.submit()"
                               class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                    </div>
                </div>
            </form>

            @if($prestamoSeleccionado)
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-xl bg-white p-5 shadow">
                        <p class="text-sm font-medium text-gray-500">Saldo sin mora</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">${{ number_format($prestamoSeleccionado->saldo_pendiente, 2) }}</p>
                    </div>

                    <div class="rounded-xl bg-white p-5 shadow">
                        <p class="text-sm font-medium text-gray-500">Mora pendiente</p>
                        <p class="mt-2 text-2xl font-bold {{ $moraPendiente > 0 ? 'text-red-700' : 'text-gray-900' }}">
                            ${{ number_format($moraPendiente, 2) }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-white p-5 shadow">
                        <p class="text-sm font-medium text-gray-500">Total para liquidar</p>
                        <p class="mt-2 text-2xl font-bold text-green-700">${{ number_format($montoMaximo, 2) }}</p>
                    </div>
                </div>

                <div>
                    <form method="POST"
                          action="{{ route('admin.pagos.store') }}"
                          enctype="multipart/form-data"
                          class="rounded-xl bg-white p-6 shadow">
                        @csrf
                        <input type="hidden" name="prestamo_id" value="{{ $prestamoSeleccionado->id }}">

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">Monto</label>
                                <input id="monto"
                                       type="number"
                                       name="monto"
                                       step="0.01"
                                       min="0.01"
                                       max="{{ $montoMaximo }}"
                                       value="{{ old('monto', number_format($montoSugerido, 2, '.', '')) }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
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
                                            class="font-semibold text-green-700 hover:text-green-800">
                                        Liquidar ${{ number_format($montoMaximo, 2) }}
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('monto')" class="mt-2" />
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">Fecha de pago</label>
                                <input type="date"
                                       name="fecha_pago"
                                       max="{{ now()->toDateString() }}"
                                       value="{{ old('fecha_pago', $fechaPagoSeleccionada) }}"
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
                            <button type="submit"
                                    name="registrar_otro"
                                    value="1"
                                    class="rounded border border-purple-200 px-5 py-3 font-semibold text-purple-700 hover:bg-purple-50">
                                Guardar y registrar otro
                            </button>

                            <button type="submit"
                                    class="rounded bg-purple-700 px-6 py-3 font-semibold text-white hover:bg-purple-800">
                                Registrar pago
                            </button>
                        </div>
                    </form>
                </div>

                <details class="overflow-hidden rounded-xl bg-white shadow">
                    <summary class="cursor-pointer border-b px-5 py-4 text-lg font-bold text-gray-800">
                        Proximas cuotas
                    </summary>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-purple-700 text-white">
                                <tr>
                                    <th class="p-4 text-left">Cuota</th>
                                    <th class="p-4 text-center">Vence</th>
                                    <th class="p-4 text-right">Saldo cuota</th>
                                    <th class="p-4 text-right">Mora</th>
                                    <th class="p-4 text-right">Por cubrir</th>
                                    <th class="p-4 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cuotasResumen as $cuota)
                                    <tr class="border-b last:border-b-0">
                                        <td class="p-4 font-semibold text-gray-800">#{{ $cuota['numero'] }}</td>
                                        <td class="p-4 text-center">{{ $cuota['fecha_vencimiento']->format('d/m/Y') }}</td>
                                        <td class="p-4 text-right">${{ number_format($cuota['saldo_cuota'], 2) }}</td>
                                        <td class="p-4 text-right {{ $cuota['mora'] > 0 ? 'font-semibold text-red-700' : '' }}">
                                            ${{ number_format($cuota['mora'], 2) }}
                                        </td>
                                        <td class="p-4 text-right font-semibold text-purple-700">
                                            ${{ number_format($cuota['por_cubrir'], 2) }}
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                                {{ $cuota['estado'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-6 text-center text-gray-500">
                                            No hay cuotas pendientes.
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
</x-app-layout>
