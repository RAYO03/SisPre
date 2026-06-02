<div class="space-y-6">
    <div class="rounded-2xl bg-white p-6 shadow">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Monto solicitado</label>
                <input type="number"
                       min="1000"
                       step="0.01"
                       wire:model.live="capital"
                       class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Plazo</label>
                <select wire:model.live="plazo_meses"
                        class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                    @foreach($plazos as $plazo)
                        <option value="{{ $plazo }}">{{ $plazo }} meses</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Fecha estimada de inicio</label>
                <input type="date"
                       wire:model.live="fecha_inicio"
                       class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-xl bg-purple-50 p-5">
                <p class="text-sm text-gray-500">Pago mensual estimado</p>
                <p class="mt-1 text-3xl font-bold text-purple-700">${{ number_format($resumen['pago_mensual'], 2) }}</p>
            </div>

            <div class="rounded-xl bg-blue-50 p-5">
                <p class="text-sm text-gray-500">Tasa anual aplicada</p>
                <p class="mt-1 text-3xl font-bold text-blue-700">{{ number_format($resumen['tasa_anual'], 2) }}%</p>
            </div>

            <div class="rounded-xl bg-emerald-50 p-5">
                <p class="text-sm text-gray-500">Total estimado a pagar</p>
                <p class="mt-1 text-3xl font-bold text-emerald-700">${{ number_format($resumen['total_pagar'], 2) }}</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('cliente.solicitud', [
                    'monto_solicitado' => $capital,
                    'plazo_meses' => $plazo_meses,
                ]) }}"
               class="rounded-xl bg-purple-700 px-6 py-3 font-semibold text-white hover:bg-purple-800">
                Continuar solicitud
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow">
        <div class="border-b px-6 py-5">
            <h2 class="text-xl font-bold text-gray-800">Tabla de amortizacion estimada</h2>
            <p class="text-sm text-gray-500">Sistema frances: cuota fija, interes sobre saldo y ajuste final por redondeo.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-purple-700 text-white">
                    <tr>
                        <th class="p-4">#</th>
                        <th class="p-4">Vencimiento</th>
                        <th class="p-4">Cuota</th>
                        <th class="p-4">Interes</th>
                        <th class="p-4">Capital</th>
                        <th class="p-4">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tabla as $fila)
                        <tr class="border-b last:border-b-0">
                            <td class="p-4">{{ $fila['numero'] }}</td>
                            <td class="p-4">{{ $fila['fecha_vencimiento'] }}</td>
                            <td class="p-4">${{ number_format($fila['cuota_total'], 2) }}</td>
                            <td class="p-4">${{ number_format($fila['interes'], 2) }}</td>
                            <td class="p-4">${{ number_format($fila['capital'], 2) }}</td>
                            <td class="p-4">${{ number_format($fila['saldo_restante'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500">
                                Ingresa un monto y plazo validos para simular.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
