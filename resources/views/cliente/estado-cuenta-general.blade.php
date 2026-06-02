<x-app-layout>
    <div class="space-y-6 p-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-purple-700">Estado de cuenta general</h1>
                <p class="mt-1 text-sm text-gray-500">Resumen de tus prestamos y pagos.</p>
            </div>

            <a href="{{ route('cliente.prestamos') }}"
               class="w-fit rounded bg-purple-700 px-5 py-3 font-semibold text-white hover:bg-purple-800">
                Mis prestamos
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm font-medium text-gray-500">Saldo pendiente</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">
                    ${{ number_format($resumen['saldo_pendiente'], 2) }}
                </p>
            </div>

            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm font-medium text-gray-500">Mora pendiente</p>
                <p class="mt-2 text-2xl font-bold {{ $resumen['mora_pendiente'] > 0 ? 'text-red-700' : 'text-gray-900' }}">
                    ${{ number_format($resumen['mora_pendiente'], 2) }}
                </p>
            </div>

            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm font-medium text-gray-500">Proxima cuota</p>
                @if($proximaCuota)
                    @php
                        $moraProxima = $proximaCuota->calcularInteresMoratorio();
                    @endphp
                    <p class="mt-2 text-2xl font-bold text-purple-700">
                        ${{ number_format($proximaCuota->saldo_pendiente + $moraProxima, 2) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ $proximaCuota->prestamo->folio ?? '' }} vence {{ $proximaCuota->fecha_vencimiento->format('d/m/Y') }}
                    </p>
                @else
                    <p class="mt-2 text-xl font-bold text-green-700">Sin cuotas pendientes</p>
                @endif
            </div>
        </div>

        <div class="overflow-hidden rounded-xl bg-white shadow">
            <div class="border-b px-5 py-4">
                <h2 class="text-lg font-bold text-gray-800">Resumen por prestamo</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-purple-700 text-white">
                        <tr>
                            <th class="p-4 text-left">Folio</th>
                            <th class="p-4 text-right">Saldo</th>
                            <th class="p-4 text-center">Vencidas</th>
                            <th class="p-4 text-center">Estado</th>
                            <th class="p-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prestamos as $prestamo)
                            @php
                                $cuotasVencidas = $prestamo->cuotas->where('estado', \App\Support\Estado::VENCIDA)->count();
                            @endphp

                            <tr class="border-b last:border-b-0">
                                <td class="p-4 font-semibold text-gray-800">{{ $prestamo->folio }}</td>
                                <td class="p-4 text-right font-semibold text-gray-900">${{ number_format($prestamo->saldo_pendiente, 2) }}</td>
                                <td class="p-4 text-center">{{ $cuotasVencidas }}</td>
                                <td class="p-4 text-center">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                                        @if($prestamo->estado === \App\Support\Estado::ACTIVO) bg-green-100 text-green-700
                                        @elseif($prestamo->estado === \App\Support\Estado::LIQUIDADO) bg-blue-100 text-blue-700
                                        @elseif($prestamo->estado === \App\Support\Estado::EN_MORA) bg-red-100 text-red-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ $prestamo->estado_label }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
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
                                <td colspan="5" class="p-6 text-center text-gray-500">
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

        <details class="overflow-hidden rounded-xl bg-white shadow">
            <summary class="cursor-pointer border-b px-5 py-4 text-lg font-bold text-gray-800">
                Detalles financieros
            </summary>

            <div class="grid grid-cols-1 gap-4 p-5 text-sm md:grid-cols-4">
                <div>
                    <p class="text-gray-500">Capital prestado</p>
                    <p class="mt-1 font-semibold text-gray-900">${{ number_format($resumen['capital_prestado'], 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Total pagado</p>
                    <p class="mt-1 font-semibold text-green-700">${{ number_format($resumen['total_pagado'], 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Interes generado</p>
                    <p class="mt-1 font-semibold text-gray-900">${{ number_format($resumen['interes_generado'], 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Saldo con mora</p>
                    <p class="mt-1 font-semibold text-purple-700">${{ number_format($resumen['saldo_con_mora'], 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Prestamos</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ $resumen['prestamos_total'] }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Activos</p>
                    <p class="mt-1 font-semibold text-green-700">{{ $resumen['prestamos_activos'] }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Liquidados</p>
                    <p class="mt-1 font-semibold text-blue-700">{{ $resumen['prestamos_liquidados'] }}</p>
                </div>
                <div>
                    <p class="text-gray-500">En mora</p>
                    <p class="mt-1 font-semibold text-red-700">{{ $resumen['prestamos_en_mora'] }}</p>
                </div>
            </div>
        </details>

        <div class="overflow-hidden rounded-xl bg-white shadow">
            <div class="border-b px-5 py-4">
                <h2 class="text-lg font-bold text-gray-800">Pagos recientes</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-4 text-left">Folio</th>
                            <th class="p-4 text-left">Prestamo</th>
                            <th class="p-4 text-center">Fecha</th>
                            <th class="p-4 text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagosRecientes as $pago)
                            <tr class="border-b last:border-b-0">
                                <td class="p-4 font-semibold text-gray-800">{{ $pago->folio_pago }}</td>
                                <td class="p-4">{{ $pago->prestamo->folio ?? 'N/A' }}</td>
                                <td class="p-4 text-center">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                                <td class="p-4 text-right font-semibold text-green-700">${{ number_format($pago->monto, 2) }}</td>
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

                @if($pagosRecientes->hasPages())
                    <div class="border-t px-6 py-4">
                        {{ $pagosRecientes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
