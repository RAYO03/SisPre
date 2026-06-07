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

    <div class="min-h-screen bg-slate-100 p-8">

        <div class="mx-auto max-w-7xl animate-fade-up">

            {{-- ENCABEZADO --}}
            <div class="mb-8">

                <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                    Reportes administrativos
                </span>

                <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                    Reportes
                </h1>

                <p class="mt-2 text-gray-500">
                    Resumen general de cartera, cobranza y desempeño financiero.
                </p>

            </div>

            {{-- TARJETAS --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">

                <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-100 text-2xl">
                        💰
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Total prestado
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-purple-700">
                        ${{ number_format($totalPrestado, 2) }}
                    </h2>
                </div>

                <div class="rounded-3xl border-t-4 border-green-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-2xl">
                        💵
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Total recuperado
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-green-700">
                        ${{ number_format($totalCobrado, 2) }}
                    </h2>
                </div>

                <div class="rounded-3xl border-t-4 border-blue-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-2xl">
                        📊
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Saldo pendiente
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-blue-700">
                        ${{ number_format($saldoPendiente, 2) }}
                    </h2>
                </div>

                <div class="rounded-3xl border-t-4 border-red-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-100 text-2xl">
                        ⚠️
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Cartera en mora
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-red-700">
                        ${{ number_format($carteraMora, 2) }}
                    </h2>
                </div>

            </div>

            {{-- RESUMENES --}}
            <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">

                <div class="rounded-3xl bg-white p-6 shadow-2xl">

                    <div class="mb-5 flex items-center justify-between">
                        <h2 class="text-2xl font-black text-gray-800">
                            Préstamos por estado
                        </h2>

                        <span class="rounded-full bg-purple-100 px-3 py-1 text-sm font-bold text-purple-700">
                            {{ array_sum($prestamosPorEstado) }} Total
                        </span>
                    </div>

                    <div class="grid grid-cols-3 gap-4">

                        <div class="rounded-2xl bg-blue-50 p-5 text-center">
                            <p class="text-3xl font-black text-blue-700">
                                {{ $prestamosPorEstado['activos'] }}
                            </p>

                            <p class="mt-2 text-sm font-semibold text-gray-500">
                                Activos
                            </p>
                        </div>

                        <div class="rounded-2xl bg-green-50 p-5 text-center">
                            <p class="text-3xl font-black text-green-700">
                                {{ $prestamosPorEstado['liquidados'] }}
                            </p>

                            <p class="mt-2 text-sm font-semibold text-gray-500">
                                Liquidados
                            </p>
                        </div>

                        <div class="rounded-2xl bg-red-50 p-5 text-center">
                            <p class="text-3xl font-black text-red-700">
                                {{ $prestamosPorEstado['en_mora'] }}
                            </p>

                            <p class="mt-2 text-sm font-semibold text-gray-500">
                                En mora
                            </p>
                        </div>

                    </div>

                </div>

                <div class="rounded-3xl bg-white p-6 shadow-2xl">

                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-2xl">
                        📈
                    </div>

                    <h2 class="text-2xl font-black text-gray-800">
                        Pagos del mes
                    </h2>

                    <p class="mt-4 text-4xl font-black text-green-700">
                        ${{ number_format($pagosMes, 2) }}
                    </p>

                    <p class="mt-3 text-sm text-gray-500">
                        Cobrado durante {{ now()->locale('es')->translatedFormat('F Y') }}
                    </p>

                </div>

            </div>

            {{-- TABLAS --}}
            <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-2">

                <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                    <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 p-6">
                        <h2 class="text-2xl font-black text-gray-800">
                            Alertas de cobranza
                        </h2>
                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full">

                            <thead class="bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 text-white shadow-lg">
                                <tr>
                                    <th class="p-4 text-left">Cliente</th>
                                    <th class="p-4 text-center">Cuota</th>
                                    <th class="p-4 text-center">Atraso</th>
                                    <th class="p-4 text-right">Pendiente</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($alertasCobranza as $cuota)
                                    <tr class="border-b hover:bg-red-50">
                                        <td class="p-4 font-semibold">{{ $cuota->prestamo->user->name ?? 'N/A' }}</td>
                                        <td class="p-4 text-center">#{{ $cuota->numero }}</td>
                                        <td class="p-4 text-center text-red-700 font-bold">{{ $cuota->dias_atraso }} días</td>
                                        <td class="p-4 text-right font-bold text-red-700">${{ number_format($cuota->saldo_pendiente, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-gray-500">
                                            No hay cuotas vencidas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                    <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 p-6">
                        <h2 class="text-2xl font-black text-gray-800">
                            Últimos pagos recibidos
                        </h2>
                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full">

                            <thead class="bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 text-white shadow-lg">
                                <tr>
                                    <th class="p-4 text-left">Cliente</th>
                                    <th class="p-4 text-center">Fecha</th>
                                    <th class="p-4 text-center">Método</th>
                                    <th class="p-4 text-right">Monto</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($ultimosPagos as $pago)
                                    <tr class="border-b hover:bg-green-50">
                                        <td class="p-4 font-semibold">{{ $pago->user->name ?? 'N/A' }}</td>
                                        <td class="p-4 text-center">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                                        <td class="p-4 text-center">{{ $pago->metodo_pago }}</td>
                                        <td class="p-4 text-right font-bold text-green-700">${{ number_format($pago->monto, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-gray-500">
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

    </div>

</x-app-layout>