<x-app-layout>
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-slide-up {
            animation: slideUp .8s ease-out forwards;
        }
    </style>

    <div class="min-h-screen bg-slate-100 px-4 py-10">
        <div class="mx-auto max-w-7xl animate-slide-up">

            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                        Estado de cuenta
                    </span>

                    <h1 class="mt-4 text-4xl font-black text-purple-700">
                        Detalle del préstamo
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Consulta pagos registrados, saldo pendiente y tabla de amortización.
                    </p>
                </div>

                @if(in_array($prestamo->estado, [\App\Support\Estado::ACTIVO, \App\Support\Estado::EN_MORA], true))
                    <a href="{{ route('cliente.pagos.create', ['prestamo_id' => $prestamo->id, 'return_to' => 'estado-cuenta']) }}"
                       class="rounded-2xl bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-green-300 transition hover:scale-105 hover:from-green-700 hover:to-emerald-700">
                        Registrar pago →
                    </a>
                @endif
            </div>

            <div class="mb-8 grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Monto original</p>
                    <h2 class="mt-2 text-2xl font-black text-gray-900">
                        ${{ number_format($prestamo->monto_original ?? $prestamo->solicitud?->monto_solicitado ?? $prestamo->monto_total, 2) }}
                    </h2>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Saldo total por pagar</p>
                    <h2 class="mt-2 text-2xl font-black text-red-600">
                        ${{ number_format($prestamo->saldo_pendiente, 2) }}
                    </h2>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Pago mensual</p>
                    <h2 class="mt-2 text-2xl font-black text-green-600">
                        ${{ number_format($prestamo->pago_mensual, 2) }}
                    </h2>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">
                <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Pagos registrados
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Historial de pagos realizados para este préstamo.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[950px] text-sm">
                        <thead class="bg-purple-700 text-white">
                            <tr>
                                <th class="p-4 text-left">Folio</th>
                                <th class="p-4 text-left">Fecha</th>
                                <th class="p-4 text-right">Monto</th>
                                <th class="p-4 text-right">Mora</th>
                                <th class="p-4 text-right">Interés</th>
                                <th class="p-4 text-right">Capital</th>
                                <th class="p-4 text-left">Método</th>
                                <th class="p-4 text-left">Estado</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($pagos as $pago)
                                <tr class="transition hover:bg-purple-50/60">
                                    <td class="p-4 font-bold text-gray-800">
                                        {{ $pago->folio_pago }}
                                    </td>

                                    <td class="p-4 text-gray-700">
                                        {{ $pago->fecha_pago }}
                                    </td>

                                    <td class="p-4 text-right font-bold text-gray-800">
                                        ${{ number_format($pago->monto, 2) }}
                                    </td>

                                    <td class="p-4 text-right text-gray-700">
                                        ${{ number_format($pago->interes_moratorio_pagado, 2) }}
                                    </td>

                                    <td class="p-4 text-right text-gray-700">
                                        ${{ number_format($pago->interes_ordinario_pagado, 2) }}
                                    </td>

                                    <td class="p-4 text-right text-gray-700">
                                        ${{ number_format($pago->capital_pagado, 2) }}
                                    </td>

                                    <td class="p-4 text-gray-700">
                                        {{ $pago->metodo_pago }}
                                    </td>

                                    <td class="p-4">
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                            {{ $pago->estado_label }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-10 text-center text-gray-500">
                                        No hay pagos registrados para este préstamo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($pagos->hasPages())
                        <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                            {{ $pagos->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">
                <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Tabla de amortización
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Cuotas reales generadas al aprobar el préstamo con sistema francés.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1100px] text-sm">
                        <thead class="bg-purple-700 text-white">
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

                        <tbody class="divide-y divide-gray-100">
                            @forelse($cuotas as $cuota)
                                @php
                                    $diasAtraso = $cuota->dias_atraso;
                                    $mora = $cuota->calcularInteresMoratorio();
                                    $porCubrir = $cuota->saldo_pendiente + $mora;
                                @endphp

                                <tr class="transition hover:bg-purple-50/60">
                                    <td class="p-4">
                                        <p class="font-bold text-gray-800">
                                            #{{ $cuota->numero }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ $cuota->fecha_vencimiento->format('d/m/Y') }}
                                        </p>
                                    </td>

                                    <td class="p-4 text-right font-bold text-gray-800">
                                        ${{ number_format($cuota->cuota_total, 2) }}
                                    </td>

                                    <td class="p-4 text-right text-xs text-gray-600">
                                        <p>Interés: ${{ number_format($cuota->interes, 2) }}</p>
                                        <p>Capital: ${{ number_format($cuota->capital, 2) }}</p>
                                    </td>

                                    <td class="p-4 text-right">
                                        <p class="font-bold text-gray-800">
                                            ${{ number_format($cuota->monto_pagado, 2) }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            de ${{ number_format($cuota->cuota_total, 2) }}
                                        </p>
                                    </td>

                                    <td class="p-4 text-right">
                                        <p class="font-bold {{ $mora > 0 ? 'text-red-600' : 'text-gray-800' }}">
                                            ${{ number_format($mora, 2) }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ $diasAtraso }} días
                                        </p>

                                        @if($cuota->mora_pagada > 0)
                                            <p class="text-xs font-semibold text-emerald-700">
                                                Pagada ${{ number_format($cuota->mora_pagada, 2) }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="p-4 text-right">
                                        <p class="font-bold text-purple-700">
                                            ${{ number_format($porCubrir, 2) }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            cuota + mora
                                        </p>
                                    </td>

                                    <td class="p-4 text-right font-bold text-gray-800">
                                        ${{ number_format($cuota->saldo_restante, 2) }}
                                    </td>

                                    <td class="p-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold
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
                                    <td colspan="8" class="p-10 text-center text-gray-500">
                                        No hay cuotas generadas para este préstamo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($cuotas->hasPages())
                        <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                            {{ $cuotas->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>