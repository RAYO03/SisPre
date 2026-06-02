<x-app-layout>
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .animate-slide-up {
            animation: slideUp .8s ease-out forwards;
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
    </style>

    <div class="min-h-screen bg-slate-100 px-4 py-10">
        <div class="mx-auto max-w-7xl animate-slide-up">

            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                        Estado financiero
                    </span>

                    <h1 class="mt-4 text-4xl font-black text-purple-700">
                        Estado de cuenta general
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Resumen global de tus préstamos, pagos, saldos, intereses y mora.
                    </p>
                </div>

                <a href="{{ route('cliente.prestamos') }}"
                   class="w-fit rounded-2xl bg-gradient-to-r from-purple-600 to-fuchsia-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105 hover:from-purple-700 hover:to-fuchsia-700">
                    Mis préstamos →
                </a>
            </div>

            <div class="mb-6 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Capital prestado</p>
                    <h2 class="mt-2 text-2xl font-black text-gray-900">
                        ${{ number_format($resumen['capital_prestado'], 2) }}
                    </h2>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Saldo pendiente</p>
                    <h2 class="mt-2 text-2xl font-black text-red-600">
                        ${{ number_format($resumen['saldo_pendiente'], 2) }}
                    </h2>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Total pagado</p>
                    <h2 class="mt-2 text-2xl font-black text-green-600">
                        ${{ number_format($resumen['total_pagado'], 2) }}
                    </h2>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Mora pendiente</p>
                    <h2 class="mt-2 text-2xl font-black text-orange-600">
                        ${{ number_format($resumen['mora_pendiente'], 2) }}
                    </h2>
                </div>
            </div>

            <div class="mb-6 grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Interés generado</p>
                    <h2 class="mt-2 text-xl font-black text-gray-900">
                        ${{ number_format($resumen['interes_generado'], 2) }}
                    </h2>
                    <p class="mt-2 text-xs text-gray-500">
                        Interés ordinario de todas las cuotas.
                    </p>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Saldo con mora</p>
                    <h2 class="mt-2 text-xl font-black text-purple-700">
                        ${{ number_format($resumen['saldo_con_mora'], 2) }}
                    </h2>
                    <p class="mt-2 text-xs text-gray-500">
                        Saldo pendiente más mora acumulada.
                    </p>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Próxima cuota</p>

                    @if($proximaCuota)
                        @php
                            $moraProxima = $proximaCuota->calcularInteresMoratorio();
                        @endphp

                        <h2 class="mt-2 text-xl font-black text-gray-900">
                            ${{ number_format($proximaCuota->saldo_pendiente + $moraProxima, 2) }}
                        </h2>

                        <p class="mt-2 text-xs text-gray-500">
                            Préstamo {{ $proximaCuota->prestamo->folio ?? '' }} |
                            vence {{ $proximaCuota->fecha_vencimiento->format('d/m/Y') }}
                        </p>
                    @else
                        <h2 class="mt-2 text-xl font-black text-gray-600">
                            Sin cuotas pendientes
                        </h2>
                    @endif
                </div>
            </div>

            <div class="mb-8 grid grid-cols-2 gap-5 md:grid-cols-5">
                <div class="rounded-3xl border border-gray-200 bg-white p-5 text-center shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-2xl font-black text-gray-900">
                        {{ $resumen['prestamos_total'] }}
                    </p>
                    <p class="mt-1 text-sm text-gray-500">Préstamos</p>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-5 text-center shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-2xl font-black text-green-600">
                        {{ $resumen['prestamos_activos'] }}
                    </p>
                    <p class="mt-1 text-sm text-gray-500">Activos</p>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-5 text-center shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-2xl font-black text-blue-600">
                        {{ $resumen['prestamos_liquidados'] }}
                    </p>
                    <p class="mt-1 text-sm text-gray-500">Liquidados</p>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-5 text-center shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-2xl font-black text-red-600">
                        {{ $resumen['prestamos_en_mora'] }}
                    </p>
                    <p class="mt-1 text-sm text-gray-500">En mora</p>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-5 text-center shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-2xl font-black text-orange-600">
                        {{ $resumen['cuotas_vencidas'] }}
                    </p>
                    <p class="mt-1 text-sm text-gray-500">Cuotas vencidas</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">
                <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Resumen por préstamo
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Detalle de tus préstamos registrados.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[950px] text-sm">
                        <thead class="bg-purple-700 text-white">
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

                        <tbody class="divide-y divide-gray-100">
                            @forelse($prestamos as $prestamo)
                                @php
                                    $cuotasVencidas = $prestamo->cuotas->where('estado', \App\Support\Estado::VENCIDA)->count();
                                    $montoSolicitado = $prestamo->monto_original ?? $prestamo->solicitud?->monto_solicitado ?? $prestamo->monto_total;
                                @endphp

                                <tr class="transition hover:bg-purple-50/60">
                                    <td class="p-4 font-bold text-gray-800">
                                        {{ $prestamo->folio }}
                                    </td>

                                    <td class="p-4 text-right text-gray-700">
                                        ${{ number_format($montoSolicitado, 2) }}
                                    </td>

                                    <td class="p-4 text-right text-gray-700">
                                        ${{ number_format($prestamo->monto_total, 2) }}
                                    </td>

                                    <td class="p-4 text-right font-bold text-red-600">
                                        ${{ number_format($prestamo->saldo_pendiente, 2) }}
                                    </td>

                                    <td class="p-4 text-right text-gray-700">
                                        {{ $cuotasVencidas }}
                                    </td>

                                    <td class="p-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold
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
                                               class="rounded-xl bg-purple-700 px-4 py-2 text-sm font-bold text-white transition hover:scale-105 hover:bg-purple-800">
                                                Ver
                                            </a>

                                            @if(in_array($prestamo->estado, [\App\Support\Estado::ACTIVO, \App\Support\Estado::EN_MORA], true))
                                                <a href="{{ route('cliente.pagos.create', ['prestamo_id' => $prestamo->id, 'return_to' => 'estado-cuenta']) }}"
                                                   class="rounded-xl bg-green-600 px-4 py-2 text-sm font-bold text-white transition hover:scale-105 hover:bg-green-700">
                                                    Pagar
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-10 text-center text-gray-500">
                                        No tienes préstamos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($prestamos->hasPages())
                        <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                            {{ $prestamos->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">
                <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Pagos recientes
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Últimos movimientos registrados.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[950px] text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="p-4 text-left">Folio</th>
                                <th class="p-4 text-left">Préstamo</th>
                                <th class="p-4 text-left">Fecha</th>
                                <th class="p-4 text-right">Monto</th>
                                <th class="p-4 text-right">Mora</th>
                                <th class="p-4 text-right">Interés</th>
                                <th class="p-4 text-right">Capital</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($pagosRecientes as $pago)
                                <tr class="transition hover:bg-purple-50/60">
                                    <td class="p-4 font-bold text-gray-800">
                                        {{ $pago->folio_pago }}
                                    </td>

                                    <td class="p-4 text-gray-700">
                                        {{ $pago->prestamo->folio ?? 'N/A' }}
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-10 text-center text-gray-500">
                                        No hay pagos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($pagosRecientes->hasPages())
                        <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                            {{ $pagosRecientes->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>