<x-app-layout>
    @php
        $moraPendiente = $prestamo->cuotas->sum(fn ($cuota) => $cuota->calcularInteresMoratorio());
        $proximaCuota = $prestamo->cuotas
            ->where('estado', '!=', \App\Support\Estado::PAGADA)
            ->sortBy('numero')
            ->first();
        $montoOriginal = $prestamo->monto_original
            ?? $prestamo->solicitud?->monto_solicitado
            ?? $prestamo->monto_total;
    @endphp

    <div class="space-y-6 p-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-purple-700">Estado de cuenta</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $prestamo->folio }}</p>
            </div>

            @if(in_array($prestamo->estado, [\App\Support\Estado::ACTIVO, \App\Support\Estado::EN_MORA], true))
                <a href="{{ route('cliente.pagos.create', ['prestamo_id' => $prestamo->id, 'return_to' => 'estado-cuenta']) }}"
                   class="w-fit rounded bg-purple-700 px-5 py-3 font-semibold text-white hover:bg-purple-800">
                    Registrar pago
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm font-medium text-gray-500">Saldo pendiente</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">${{ number_format($prestamo->saldo_pendiente, 2) }}</p>
            </div>

            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm font-medium text-gray-500">Mora pendiente</p>
                <p class="mt-2 text-2xl font-bold {{ $moraPendiente > 0 ? 'text-red-700' : 'text-gray-900' }}">
                    ${{ number_format($moraPendiente, 2) }}
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
                        #{{ $proximaCuota->numero }} vence {{ $proximaCuota->fecha_vencimiento->format('d/m/Y') }}
                    </p>
                @else
                    <p class="mt-2 text-xl font-bold text-green-700">Sin cuotas pendientes</p>
                @endif
            </div>
        </div>

        <div class="overflow-hidden rounded-xl bg-white shadow">
            <div class="border-b px-5 py-4">
                <h2 class="text-lg font-bold text-gray-800">Cuotas</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-purple-700 text-white">
                        <tr>
                            <th class="p-4 text-left">Cuota</th>
                            <th class="p-4 text-center">Vence</th>
                            <th class="p-4 text-right">Por cubrir</th>
                            <th class="p-4 text-right">Capital restante</th>
                            <th class="p-4 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cuotas as $cuota)
                            @php
                                $mora = $cuota->calcularInteresMoratorio();
                                $porCubrir = $cuota->saldo_pendiente + $mora;
                            @endphp
                            <tr class="border-b last:border-b-0">
                                <td class="p-4 font-semibold text-gray-800">#{{ $cuota->numero }}</td>
                                <td class="p-4 text-center">{{ $cuota->fecha_vencimiento->format('d/m/Y') }}</td>
                                <td class="p-4 text-right font-semibold text-purple-700">
                                    ${{ number_format($porCubrir, 2) }}
                                </td>
                                <td class="p-4 text-right font-semibold text-gray-800">
                                    ${{ number_format($cuota->saldo_restante, 2) }}
                                </td>
                                <td class="p-4 text-center">
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
                                <td colspan="5" class="p-6 text-center text-gray-500">
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

        <details class="overflow-hidden rounded-xl bg-white shadow">
            <summary class="cursor-pointer border-b px-5 py-4 text-lg font-bold text-gray-800">
                Detalles del prestamo
            </summary>

            <div class="grid grid-cols-1 gap-4 p-5 text-sm md:grid-cols-3">
                <div>
                    <p class="text-gray-500">Monto original</p>
                    <p class="mt-1 font-semibold text-gray-900">${{ number_format($montoOriginal, 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Pago mensual</p>
                    <p class="mt-1 font-semibold text-gray-900">${{ number_format($prestamo->pago_mensual, 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Tasa</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ number_format($prestamo->tasa_interes, 2) }}%</p>
                </div>
                <div>
                    <p class="text-gray-500">Plazo</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ $prestamo->plazo_meses }} meses</p>
                </div>
                <div>
                    <p class="text-gray-500">Inicio</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ \Carbon\Carbon::parse($prestamo->fecha_inicio)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Final</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ \Carbon\Carbon::parse($prestamo->fecha_final)->format('d/m/Y') }}</p>
                </div>
            </div>
        </details>

        <div class="overflow-hidden rounded-xl bg-white shadow">
            <div class="border-b px-5 py-4">
                <h2 class="text-lg font-bold text-gray-800">Pagos registrados</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-4 text-left">Folio</th>
                            <th class="p-4 text-center">Fecha</th>
                            <th class="p-4 text-right">Monto</th>
                            <th class="p-4 text-center">Metodo</th>
                            <th class="p-4 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagos as $pago)
                            <tr class="border-b last:border-b-0">
                                <td class="p-4 font-medium text-gray-800">{{ $pago->folio_pago }}</td>
                                <td class="p-4 text-center">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                                <td class="p-4 text-right font-semibold text-green-700">${{ number_format($pago->monto, 2) }}</td>
                                <td class="p-4 text-center">{{ $pago->metodo_pago }}</td>
                                <td class="p-4 text-center">
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        {{ $pago->estado_label }}
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

                @if($pagos->hasPages())
                    <div class="border-t px-6 py-4">
                        {{ $pagos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
