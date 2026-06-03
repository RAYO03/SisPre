<x-app-layout>

    <style>
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%,100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-8px);
            }
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

            {{-- ENCABEZADO --}}
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div>

                    <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                        Estado financiero
                    </span>

                    <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                        Estado de Cuenta General
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Resumen completo de préstamos, cuotas y pagos realizados.
                    </p>

                </div>

                <a href="{{ route('cliente.prestamos') }}"
                   class="rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-6 py-3 font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105">
                    Mis préstamos
                </a>

            </div>

            {{-- RESUMEN --}}
            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">

                <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                    <div class="mb-4 text-4xl animate-float">
                        💰
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Saldo pendiente
                    </p>

                    <h2 class="mt-3 text-4xl font-black text-purple-700">
                        ${{ number_format($resumen['saldo_pendiente'], 2) }}
                    </h2>

                </div>

                <div class="rounded-3xl border-t-4 border-red-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                    <div class="mb-4 text-4xl animate-float">
                        ⚠️
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Mora pendiente
                    </p>

                    <h2 class="mt-3 text-4xl font-black {{ $resumen['mora_pendiente'] > 0 ? 'text-red-600' : 'text-gray-700' }}">
                        ${{ number_format($resumen['mora_pendiente'], 2) }}
                    </h2>

                </div>

                <div class="rounded-3xl border-t-4 border-green-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                    <div class="mb-4 text-4xl animate-float">
                        📅
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Próxima cuota
                    </p>

                    @if($proximaCuota)

                        @php
                            $moraProxima = $proximaCuota->calcularInteresMoratorio();
                        @endphp

                        <h2 class="mt-3 text-4xl font-black text-green-600">
                            ${{ number_format($proximaCuota->saldo_pendiente + $moraProxima, 2) }}
                        </h2>

                        <p class="mt-2 text-sm text-gray-500">
                            {{ $proximaCuota->prestamo->folio ?? '' }}
                            vence
                            {{ $proximaCuota->fecha_vencimiento->format('d/m/Y') }}
                        </p>

                    @else

                        <h2 class="mt-3 text-xl font-black text-green-700">
                            Sin cuotas pendientes
                        </h2>

                    @endif

                </div>

            </div>

            {{-- PRESTAMOS --}}
            <div class="mb-8 overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                    <div class="flex items-center gap-4">

                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-600 to-fuchsia-600 text-3xl text-white shadow-xl shadow-purple-300 animate-float">
                            📋
                        </div>

                        <div>

                            <h2 class="text-3xl font-black text-gray-800">
                                Resumen por préstamo
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Estado actual de todos tus préstamos.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[1000px]">

                        <thead>

                            <tr class="bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 text-white">

                                <th class="p-5 text-left">Folio</th>
                                <th class="p-5 text-right">Saldo</th>
                                <th class="p-5 text-center">Vencidas</th>
                                <th class="p-5 text-center">Estado</th>
                                <th class="p-5 text-center">Acciones</th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($prestamos as $prestamo)

                                @php
                                    $cuotasVencidas = $prestamo->cuotas
                                        ->where('estado', \App\Support\Estado::VENCIDA)
                                        ->count();
                                @endphp

                                <tr class="transition duration-300 hover:bg-purple-50">

                                    <td class="p-5 font-black text-gray-800">
                                        {{ $prestamo->folio }}
                                    </td>

                                    <td class="p-5 text-right font-black text-purple-700">
                                        ${{ number_format($prestamo->saldo_pendiente, 2) }}
                                    </td>

                                    <td class="p-5 text-center font-semibold">
                                        {{ $cuotasVencidas }}
                                    </td>

                                    <td class="p-5 text-center">

                                        <span class="rounded-full px-4 py-2 text-xs font-bold

                                            @if($prestamo->estado === \App\Support\Estado::ACTIVO)
                                                bg-green-100 text-green-700
                                            @elseif($prestamo->estado === \App\Support\Estado::LIQUIDADO)
                                                bg-blue-100 text-blue-700
                                            @elseif($prestamo->estado === \App\Support\Estado::EN_MORA)
                                                bg-red-100 text-red-700
                                            @else
                                                bg-gray-100 text-gray-700
                                            @endif">

                                            {{ $prestamo->estado_label }}

                                        </span>

                                    </td>

                                    <td class="p-5">

                                        <div class="flex justify-center gap-3">

                                            <a href="{{ route('cliente.estado-cuenta', $prestamo->id) }}"
                                               class="rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105">
                                                Ver
                                            </a>

                                            @if(in_array($prestamo->estado, [\App\Support\Estado::ACTIVO, \App\Support\Estado::EN_MORA], true))

                                                <a href="{{ route('cliente.pagos.create', ['prestamo_id' => $prestamo->id, 'return_to' => 'estado-cuenta']) }}"
                                                   class="rounded-2xl bg-gradient-to-r from-green-600 to-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-green-300 transition hover:scale-105">
                                                    Pagar
                                                </a>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="p-14 text-center">

                                        <div class="mx-auto flex max-w-md flex-col items-center">

                                            <div class="mb-5 flex h-24 w-24 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-100 to-fuchsia-100 text-5xl animate-float">
                                                📋
                                            </div>

                                            <h3 class="text-2xl font-black text-gray-800">
                                                No tienes préstamos registrados
                                            </h3>

                                        </div>

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

            {{-- DETALLES FINANCIEROS --}}
            <details class="mb-8 overflow-hidden rounded-3xl bg-white shadow-2xl">

                <summary class="cursor-pointer bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6 text-xl font-black text-gray-800">
                    Detalles financieros
                </summary>

                <div class="grid grid-cols-1 gap-6 p-8 md:grid-cols-4">

                    <div>
                        <p class="text-gray-500">Capital prestado</p>
                        <p class="mt-2 text-xl font-black text-gray-800">
                            ${{ number_format($resumen['capital_prestado'], 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Total pagado</p>
                        <p class="mt-2 text-xl font-black text-green-600">
                            ${{ number_format($resumen['total_pagado'], 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Interés generado</p>
                        <p class="mt-2 text-xl font-black text-gray-800">
                            ${{ number_format($resumen['interes_generado'], 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Saldo con mora</p>
                        <p class="mt-2 text-xl font-black text-purple-700">
                            ${{ number_format($resumen['saldo_con_mora'], 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Préstamos</p>
                        <p class="mt-2 text-xl font-black text-gray-800">
                            {{ $resumen['prestamos_total'] }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Activos</p>
                        <p class="mt-2 text-xl font-black text-green-600">
                            {{ $resumen['prestamos_activos'] }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Liquidados</p>
                        <p class="mt-2 text-xl font-black text-blue-600">
                            {{ $resumen['prestamos_liquidados'] }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">En mora</p>
                        <p class="mt-2 text-xl font-black text-red-600">
                            {{ $resumen['prestamos_en_mora'] }}
                        </p>
                    </div>

                </div>

            </details>

            {{-- PAGOS RECIENTES --}}
            <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                    <h2 class="text-3xl font-black text-gray-800">
                        Pagos recientes
                    </h2>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[900px]">

                        <thead>

                            <tr class="bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 text-white">

                                <th class="p-5 text-left">Folio</th>
                                <th class="p-5 text-left">Préstamo</th>
                                <th class="p-5 text-center">Fecha</th>
                                <th class="p-5 text-right">Monto</th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($pagosRecientes as $pago)

                                <tr class="transition duration-300 hover:bg-purple-50">

                                    <td class="p-5 font-black text-gray-800">
                                        {{ $pago->folio_pago }}
                                    </td>

                                    <td class="p-5">
                                        {{ $pago->prestamo->folio ?? 'N/A' }}
                                    </td>

                                    <td class="p-5 text-center">
                                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                                    </td>

                                    <td class="p-5 text-right font-black text-green-600">
                                        ${{ number_format($pago->monto, 2) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4" class="p-14 text-center text-gray-500">
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

    </div>

</x-app-layout>