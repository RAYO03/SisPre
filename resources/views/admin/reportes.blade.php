<x-app-layout>
    <div class="space-y-6 p-8">
        <div>
            <h1 class="text-3xl font-bold text-purple-700">Reportes</h1>
            <p class="mt-1 text-sm text-gray-500">Resumen administrativo de cartera y cobranza.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm font-medium text-gray-500">Total prestado</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">${{ number_format($totalPrestado, 2) }}</p>
            </div>

            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm font-medium text-gray-500">Total recuperado</p>
                <p class="mt-2 text-2xl font-bold text-green-700">${{ number_format($totalCobrado, 2) }}</p>
            </div>

            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm font-medium text-gray-500">Saldo pendiente</p>
                <p class="mt-2 text-2xl font-bold text-blue-700">${{ number_format($saldoPendiente, 2) }}</p>
            </div>

            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm font-medium text-gray-500">Cartera en mora</p>
                <p class="mt-2 text-2xl font-bold text-red-700">${{ number_format($carteraMora, 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="rounded-xl bg-white p-5 shadow">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">Prestamos por estado</h2>
                    <span class="text-sm font-semibold text-gray-500">
                        {{ array_sum($prestamosPorEstado) }} total
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="rounded-lg bg-blue-50 p-4 text-center">
                        <p class="text-2xl font-bold text-blue-700">{{ $prestamosPorEstado['activos'] }}</p>
                        <p class="mt-1 text-xs font-semibold text-gray-500">Activos</p>
                    </div>

                    <div class="rounded-lg bg-green-50 p-4 text-center">
                        <p class="text-2xl font-bold text-green-700">{{ $prestamosPorEstado['liquidados'] }}</p>
                        <p class="mt-1 text-xs font-semibold text-gray-500">Liquidados</p>
                    </div>

                    <div class="rounded-lg bg-red-50 p-4 text-center">
                        <p class="text-2xl font-bold text-red-700">{{ $prestamosPorEstado['en_mora'] }}</p>
                        <p class="mt-1 text-xs font-semibold text-gray-500">En mora</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl bg-white p-5 shadow">
                <h2 class="text-lg font-bold text-gray-800">Pagos del mes</h2>
                <p class="mt-4 text-3xl font-bold text-green-700">${{ number_format($pagosMes, 2) }}</p>
                <p class="mt-2 text-sm text-gray-500">Cobrado durante {{ now()->locale('es')->translatedFormat('F Y') }}.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="overflow-hidden rounded-xl bg-white shadow">
                <div class="border-b px-5 py-4">
                    <h2 class="text-lg font-bold text-gray-800">Alertas de cobranza</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-purple-700 text-white">
                            <tr>
                                <th class="p-4 text-left">Cliente</th>
                                <th class="p-4 text-center">Cuota</th>
                                <th class="p-4 text-center">Atraso</th>
                                <th class="p-4 text-right">Pendiente</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alertasCobranza as $cuota)
                                <tr class="border-b last:border-b-0">
                                    <td class="p-4 font-medium text-gray-800">
                                        {{ $cuota->prestamo->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="p-4 text-center">
                                        #{{ $cuota->numero }}
                                    </td>
                                    <td class="p-4 text-center">
                                        {{ $cuota->dias_atraso }} dias
                                    </td>
                                    <td class="p-4 text-right font-semibold text-red-700">
                                        ${{ number_format($cuota->saldo_pendiente, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-gray-500">
                                        No hay cuotas vencidas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white shadow">
                <div class="border-b px-5 py-4">
                    <h2 class="text-lg font-bold text-gray-800">Ultimos pagos recibidos</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-purple-700 text-white">
                            <tr>
                                <th class="p-4 text-left">Cliente</th>
                                <th class="p-4 text-center">Fecha</th>
                                <th class="p-4 text-center">Metodo</th>
                                <th class="p-4 text-right">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosPagos as $pago)
                                <tr class="border-b last:border-b-0">
                                    <td class="p-4 font-medium text-gray-800">
                                        {{ $pago->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="p-4 text-center">
                                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                                    </td>
                                    <td class="p-4 text-center">
                                        {{ $pago->metodo_pago }}
                                    </td>
                                    <td class="p-4 text-right font-semibold text-green-700">
                                        ${{ number_format($pago->monto, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-gray-500">
                                        No hay pagos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
