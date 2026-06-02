<x-app-layout>
    <div class="p-8">
        <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-purple-700">
                    Estado de cuenta general
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Resumen global de tus prestamos, pagos, saldos, intereses y mora.
                </p>
            </div>

            <a href="{{ route('cliente.prestamos') }}"
               class="w-fit rounded-lg bg-purple-700 px-5 py-3 font-semibold text-white hover:bg-purple-800">
                Mis prestamos
            </a>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Capital prestado</p>
                <h2 class="mt-2 text-2xl font-bold text-gray-900">
                    ${{ number_format($resumen['capital_prestado'], 2) }}
                </h2>
            </div>

            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Saldo pendiente</p>
                <h2 class="mt-2 text-2xl font-bold text-red-600">
                    ${{ number_format($resumen['saldo_pendiente'], 2) }}
                </h2>
            </div>

            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Total pagado</p>
                <h2 class="mt-2 text-2xl font-bold text-green-600">
                    ${{ number_format($resumen['total_pagado'], 2) }}
                </h2>
            </div>

            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Mora pendiente</p>
                <h2 class="mt-2 text-2xl font-bold text-orange-600">
                    ${{ number_format($resumen['mora_pendiente'], 2) }}
                </h2>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-5 md:grid-cols-3">
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Interes generado</p>
                <h2 class="mt-2 text-xl font-bold text-gray-900">
                    ${{ number_format($resumen['interes_generado'], 2) }}
                </h2>
                <p class="mt-2 text-xs text-gray-500">
                    Interes ordinario de todas las cuotas.
                </p>
            </div>

            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Saldo con mora</p>
                <h2 class="mt-2 text-xl font-bold text-purple-700">
                    ${{ number_format($resumen['saldo_con_mora'], 2) }}
                </h2>
                <p class="mt-2 text-xs text-gray-500">
                    Saldo pendiente mas mora acumulada.
                </p>
            </div>

            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Proxima cuota</p>
                @if($proximaCuota)
                    @php
                        $moraProxima = $proximaCuota->calcularInteresMoratorio();
                    @endphp
                    <h2 class="mt-2 text-xl font-bold text-gray-900">
                        ${{ number_format($proximaCuota->saldo_pendiente + $moraProxima, 2) }}
                    </h2>
                    <p class="mt-2 text-xs text-gray-500">
                        Prestamo {{ $proximaCuota->prestamo->folio ?? '' }} | vence {{ $proximaCuota->fecha_vencimiento->format('d/m/Y') }}
                    </p>
                @else
                    <h2 class="mt-2 text-xl font-bold text-gray-600">
                        Sin cuotas pendientes
                    </h2>
                @endif
            </div>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-5 md:grid-cols-5">
            <div class="rounded-xl bg-white p-5 text-center shadow">
                <p class="text-2xl font-bold text-gray-900">{{ $resumen['prestamos_total'] }}</p>
                <p class="mt-1 text-sm text-gray-500">Prestamos</p>
            </div>

            <div class="rounded-xl bg-white p-5 text-center shadow">
                <p class="text-2xl font-bold text-green-600">{{ $resumen['prestamos_activos'] }}</p>
                <p class="mt-1 text-sm text-gray-500">Activos</p>
            </div>

            <div class="rounded-xl bg-white p-5 text-center shadow">
                <p class="text-2xl font-bold text-blue-600">{{ $resumen['prestamos_liquidados'] }}</p>
                <p class="mt-1 text-sm text-gray-500">Liquidados</p>
            </div>

            <div class="rounded-xl bg-white p-5 text-center shadow">
                <p class="text-2xl font-bold text-red-600">{{ $resumen['prestamos_en_mora'] }}</p>
                <p class="mt-1 text-sm text-gray-500">En mora</p>
            </div>

            <div class="rounded-xl bg-white p-5 text-center shadow">
                <p class="text-2xl font-bold text-orange-600">{{ $resumen['cuotas_vencidas'] }}</p>
                <p class="mt-1 text-sm text-gray-500">Cuotas vencidas</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl bg-white shadow">
            <div class="border-b px-6 py-5">
                <h2 class="text-xl font-bold text-gray-800">Resumen por prestamo</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-purple-600 text-white">
                        <tr>
                            <th class="p-4 text-left">Folio</th>
                            <th class="p-4 text-right">Solicitado</th>
                            <th class="p-4 text-right">Total a pagar</th>
                            <th class="p-4 text-right">Saldo</th>
                            <th class="p-4 text-right">Vencidas</th>
                            <th class="p-4 text-left">Estado</th>
                            <th class="p-4 text-left">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($prestamos as $prestamo)
                            @php
                                $cuotasVencidas = $prestamo->cuotas->where('estado', \App\Support\Estado::VENCIDA)->count();
                                $montoSolicitado = $prestamo->monto_original ?? $prestamo->solicitud?->monto_solicitado ?? $prestamo->monto_total;
                            @endphp

                            <tr class="border-b last:border-b-0">
                                <td class="p-4 font-semibold text-gray-800">{{ $prestamo->folio }}</td>
                                <td class="p-4 text-right">${{ number_format($montoSolicitado, 2) }}</td>
                                <td class="p-4 text-right">${{ number_format($prestamo->monto_total, 2) }}</td>
                                <td class="p-4 text-right font-semibold text-red-600">${{ number_format($prestamo->saldo_pendiente, 2) }}</td>
                                <td class="p-4 text-right">{{ $cuotasVencidas }}</td>
                                <td class="p-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                                        @if($prestamo->estado === \App\Support\Estado::ACTIVO) bg-green-100 text-green-700
                                        @elseif($prestamo->estado === \App\Support\Estado::LIQUIDADO) bg-blue-100 text-blue-700
                                        @elseif($prestamo->estado === \App\Support\Estado::EN_MORA) bg-red-100 text-red-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ $prestamo->estado_label }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('cliente.estado-cuenta', $prestamo->id) }}"
                                           class="rounded bg-purple-700 px-4 py-2 text-white hover:bg-purple-800">
                                            Ver
                                        </a>

                                        @if(in_array($prestamo->estado, [\App\Support\Estado::ACTIVO, \App\Support\Estado::EN_MORA], true))
                                            <a href="{{ route('cliente.pagos.create', ['prestamo_id' => $prestamo->id, 'return_to' => 'estado-cuenta']) }}"
                                               class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                                Pagar
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-gray-500">
                                    No tienes prestamos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($prestamos->hasPages())
                    <div class="border-t px-6 py-4">
                        {{ $prestamos->links() }}
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 overflow-hidden rounded-xl bg-white shadow">
            <div class="border-b px-6 py-5">
                <h2 class="text-xl font-bold text-gray-800">Pagos recientes</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-4 text-left">Folio</th>
                            <th class="p-4 text-left">Prestamo</th>
                            <th class="p-4 text-left">Fecha</th>
                            <th class="p-4 text-right">Monto</th>
                            <th class="p-4 text-right">Mora</th>
                            <th class="p-4 text-right">Interes</th>
                            <th class="p-4 text-right">Capital</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pagosRecientes as $pago)
                            <tr class="border-b last:border-b-0">
                                <td class="p-4 font-semibold text-gray-800">{{ $pago->folio_pago }}</td>
                                <td class="p-4">{{ $pago->prestamo->folio ?? 'N/A' }}</td>
                                <td class="p-4">{{ $pago->fecha_pago }}</td>
                                <td class="p-4 text-right">${{ number_format($pago->monto, 2) }}</td>
                                <td class="p-4 text-right">${{ number_format($pago->interes_moratorio_pagado, 2) }}</td>
                                <td class="p-4 text-right">${{ number_format($pago->interes_ordinario_pagado, 2) }}</td>
                                <td class="p-4 text-right">${{ number_format($pago->capital_pagado, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-gray-500">
                                    No hay pagos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($pagosRecientes->hasPages())
                    <div class="border-t px-6 py-4">
                        {{ $pagosRecientes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
