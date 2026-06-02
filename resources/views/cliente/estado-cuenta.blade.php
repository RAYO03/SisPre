<x-app-layout>
    <div class="p-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-purple-700">
                Detalle del prestamo
            </h1>

            @if(in_array($prestamo->estado, [\App\Support\Estado::ACTIVO, \App\Support\Estado::EN_MORA], true))
                <a href="{{ route('cliente.pagos.create', ['prestamo_id' => $prestamo->id, 'return_to' => 'estado-cuenta']) }}"
                   class="rounded bg-green-600 px-5 py-3 font-semibold text-white hover:bg-green-700">
                    Registrar pago
                </a>
            @endif
        </div>

        <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-xl bg-white p-6 shadow">
                <p class="text-gray-500">Monto original</p>
                <h2 class="text-2xl font-bold">
                    ${{ number_format($prestamo->monto_original ?? $prestamo->solicitud?->monto_solicitado ?? $prestamo->monto_total, 2) }}
                </h2>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <p class="text-gray-500">Saldo total por pagar</p>
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
            <div class="border-b px-6 py-5">
                <h2 class="text-xl font-bold text-gray-800">Pagos registrados</h2>
            </div>

            <table class="w-full">
                <thead class="bg-purple-600 text-white">
                    <tr>
                        <th class="p-4 text-left">Folio</th>
                        <th class="p-4 text-left">Fecha</th>
                        <th class="p-4 text-right">Monto</th>
                        <th class="p-4 text-right">Mora</th>
                        <th class="p-4 text-right">Interes</th>
                        <th class="p-4 text-right">Capital</th>
                        <th class="p-4 text-left">Metodo</th>
                        <th class="p-4 text-left">Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pagos as $pago)
                        <tr class="border-b">
                            <td class="p-4">{{ $pago->folio_pago }}</td>
                            <td class="p-4">{{ $pago->fecha_pago }}</td>
                            <td class="p-4 text-right">${{ number_format($pago->monto, 2) }}</td>
                            <td class="p-4 text-right">${{ number_format($pago->interes_moratorio_pagado, 2) }}</td>
                            <td class="p-4 text-right">${{ number_format($pago->interes_ordinario_pagado, 2) }}</td>
                            <td class="p-4 text-right">${{ number_format($pago->capital_pagado, 2) }}</td>
                            <td class="p-4">{{ $pago->metodo_pago }}</td>
                            <td class="p-4">
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    {{ $pago->estado_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-gray-500">
                                No hay pagos registrados para este prestamo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($pagos->hasPages())
                <div class="border-t px-6 py-4">
                    {{ $pagos->links() }}
                </div>
            @endif
        </div>

        <div class="mt-8 overflow-hidden rounded-xl bg-white shadow">
            <div class="border-b px-6 py-5">
                <h2 class="text-xl font-bold text-gray-800">Tabla de amortizacion</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Cuotas reales generadas al aprobar el prestamo con sistema frances.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-purple-600 text-white">
                        <tr>
                            <th class="p-4 text-left">Cuota</th>
                            <th class="p-4 text-right">Importe</th>
                            <th class="p-4 text-right">Desglose</th>
                            <th class="p-4 text-right">Pagado</th>
                            <th class="p-4 text-right">Mora</th>
                            <th class="p-4 text-right">Por cubrir</th>
                            <th class="p-4 text-right">Capital restante</th>
                            <th class="p-4 text-left">Estado</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($cuotas as $cuota)
                            @php
                                $diasAtraso = $cuota->dias_atraso;
                                $mora = $cuota->calcularInteresMoratorio();
                                $porCubrir = $cuota->saldo_pendiente + $mora;
                            @endphp
                            <tr class="border-b last:border-b-0">
                                <td class="p-4">
                                    <p class="font-semibold text-gray-800">#{{ $cuota->numero }}</p>
                                    <p class="text-xs text-gray-500">{{ $cuota->fecha_vencimiento->format('d/m/Y') }}</p>
                                </td>
                                <td class="p-4 text-right font-semibold text-gray-800">
                                    ${{ number_format($cuota->cuota_total, 2) }}
                                </td>
                                <td class="p-4 text-right text-xs text-gray-600">
                                    <p>Interes: ${{ number_format($cuota->interes, 2) }}</p>
                                    <p>Capital: ${{ number_format($cuota->capital, 2) }}</p>
                                </td>
                                <td class="p-4 text-right">
                                    <p class="font-semibold text-gray-800">${{ number_format($cuota->monto_pagado, 2) }}</p>
                                    <p class="text-xs text-gray-500">de ${{ number_format($cuota->cuota_total, 2) }}</p>
                                </td>
                                <td class="p-4 text-right">
                                    <p class="font-semibold {{ $mora > 0 ? 'text-red-600' : 'text-gray-800' }}">
                                        ${{ number_format($mora, 2) }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $diasAtraso }} dias</p>
                                    @if($cuota->mora_pagada > 0)
                                        <p class="text-xs text-emerald-700">
                                            pagada ${{ number_format($cuota->mora_pagada, 2) }}
                                        </p>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <p class="font-semibold text-purple-700">${{ number_format($porCubrir, 2) }}</p>
                                    <p class="text-xs text-gray-500">cuota + mora</p>
                                </td>
                                <td class="p-4 text-right font-semibold text-gray-800">
                                    ${{ number_format($cuota->saldo_restante, 2) }}
                                </td>
                                <td class="p-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                                        @if($cuota->estado === \App\Support\Estado::PENDIENTE) bg-yellow-100 text-yellow-700
                                        @elseif($cuota->estado === \App\Support\Estado::PAGADA) bg-green-100 text-green-700
                                        @elseif($cuota->estado === \App\Support\Estado::VENCIDA) bg-red-100 text-red-700
                                        @elseif($cuota->estado === \App\Support\Estado::PARCIALMENTE_PAGADA) bg-blue-100 text-blue-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ $cuota->estado_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-gray-500">
                                    No hay cuotas generadas para este prestamo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($cuotas->hasPages())
                    <div class="border-t px-6 py-4">
                        {{ $cuotas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
