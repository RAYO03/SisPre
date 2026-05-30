<x-app-layout>
    <div class="p-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-purple-700">
                Estado de Cuenta
            </h1>

            @if($prestamo->estado === 'activo')
                <a href="{{ route('cliente.pagos.create', $prestamo->id) }}"
                   class="rounded bg-green-600 px-5 py-3 font-semibold text-white hover:bg-green-700">
                    Registrar pago
                </a>
            @endif
        </div>

        <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-xl bg-white p-6 shadow">
                <p class="text-gray-500">Monto original</p>
                <h2 class="text-2xl font-bold">
                    ${{ number_format($prestamo->monto_total, 2) }}
                </h2>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <p class="text-gray-500">Saldo pendiente</p>
                <h2 class="text-2xl font-bold text-red-600">
                    ${{ number_format($prestamo->saldo_pendiente, 2) }}
                </h2>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <p class="text-gray-500">Pago mensual</p>
                <h2 class="text-2xl font-bold text-green-600">
                    ${{ number_format($prestamo->pago_mensual, 2) }}
                </h2>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-purple-600 text-white">
                    <tr>
                        <th class="p-4 text-left">Folio</th>
                        <th class="p-4 text-left">Fecha</th>
                        <th class="p-4 text-right">Monto</th>
                        <th class="p-4 text-left">Metodo</th>
                        <th class="p-4 text-left">Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($prestamo->pagos as $pago)
                        <tr class="border-b">
                            <td class="p-4">{{ $pago->folio_pago }}</td>
                            <td class="p-4">{{ $pago->fecha_pago }}</td>
                            <td class="p-4 text-right">${{ number_format($pago->monto, 2) }}</td>
                            <td class="p-4">{{ $pago->metodo_pago }}</td>
                            <td class="p-4">
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    {{ ucfirst($pago->estado) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">
                                No hay pagos registrados para este prestamo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
