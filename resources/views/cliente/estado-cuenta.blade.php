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
                        Estado de Cuenta
                    </h1>

                    <p class="mt-2 text-gray-500">
                        {{ $prestamo->folio }}
                    </p>

                </div>

                @if(in_array($prestamo->estado, [\App\Support\Estado::ACTIVO, \App\Support\Estado::EN_MORA], true))

                    <a href="{{ route('cliente.pagos.create', ['prestamo_id' => $prestamo->id, 'return_to' => 'estado-cuenta']) }}"
                       class="rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-6 py-3 font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105">
                        Registrar pago
                    </a>

                @endif

            </div>

            {{-- TARJETAS --}}
            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">

                <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                    <div class="mb-4 text-4xl animate-float">
                        💰
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Saldo pendiente
                    </p>

                    <h2 class="mt-3 text-4xl font-black text-purple-700">
                        ${{ number_format($prestamo->saldo_pendiente, 2) }}
                    </h2>

                </div>

                <div class="rounded-3xl border-t-4 border-red-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                    <div class="mb-4 text-4xl animate-float">
                        ⚠️
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Mora pendiente
                    </p>

                    <h2 class="mt-3 text-4xl font-black {{ $moraPendiente > 0 ? 'text-red-600' : 'text-gray-700' }}">
                        ${{ number_format($moraPendiente, 2) }}
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
                            #{{ $proximaCuota->numero }}
                            vence {{ $proximaCuota->fecha_vencimiento->format('d/m/Y') }}
                        </p>

                    @else

                        <h2 class="mt-3 text-xl font-black text-green-700">
                            Sin cuotas pendientes
                        </h2>

                    @endif

                </div>

            </div>

            {{-- CUOTAS --}}
            <div class="mb-8 overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                    <div class="flex items-center gap-4">

                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-600 to-fuchsia-600 text-3xl text-white shadow-xl shadow-purple-300 animate-float">
                            📋
                        </div>

                        <div>

                            <h2 class="text-3xl font-black text-gray-800">
                                Cuotas
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Seguimiento de pagos pendientes y realizados.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[1000px]">

                        <thead>

                            <tr class="bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 text-white">

                                <th class="p-5 text-left">Cuota</th>
                                <th class="p-5 text-center">Vence</th>
                                <th class="p-5 text-right">Por cubrir</th>
                                <th class="p-5 text-right">Capital restante</th>
                                <th class="p-5 text-center">Estado</th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($cuotas as $cuota)

                                @php
                                    $mora = $cuota->calcularInteresMoratorio();
                                    $porCubrir = $cuota->saldo_pendiente + $mora;
                                @endphp

                                <tr class="transition duration-300 hover:bg-purple-50">

                                    <td class="p-5 font-black text-gray-800">
                                        #{{ $cuota->numero }}
                                    </td>

                                    <td class="p-5 text-center font-semibold text-gray-700">
                                        {{ $cuota->fecha_vencimiento->format('d/m/Y') }}
                                    </td>

                                    <td class="p-5 text-right font-black text-purple-700">
                                        ${{ number_format($porCubrir, 2) }}
                                    </td>

                                    <td class="p-5 text-right font-semibold text-gray-800">
                                        ${{ number_format($cuota->saldo_restante, 2) }}
                                    </td>

                                    <td class="p-5 text-center">

                                        <span class="rounded-full px-4 py-2 text-xs font-bold

                                            @if($cuota->estado === \App\Support\Estado::PENDIENTE)
                                                bg-yellow-100 text-yellow-700
                                            @elseif($cuota->estado === \App\Support\Estado::PAGADA)
                                                bg-green-100 text-green-700
                                            @elseif($cuota->estado === \App\Support\Estado::VENCIDA)
                                                bg-red-100 text-red-700
                                            @elseif($cuota->estado === \App\Support\Estado::PARCIALMENTE_PAGADA)
                                                bg-blue-100 text-blue-700
                                            @else
                                                bg-gray-100 text-gray-700
                                            @endif">

                                            {{ $cuota->estado_label }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="p-10 text-center text-gray-500">
                                        No hay cuotas generadas para este préstamo.
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

            {{-- DETALLES --}}
            <details class="mb-8 overflow-hidden rounded-3xl bg-white shadow-2xl">

                <summary class="cursor-pointer bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6 text-xl font-black text-gray-800">
                    Detalles del préstamo
                </summary>

                <div class="grid grid-cols-1 gap-6 p-8 md:grid-cols-3">

                    <div>
                        <p class="text-gray-500">Monto original</p>
                        <p class="mt-2 text-xl font-black text-gray-800">
                            ${{ number_format($montoOriginal, 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Pago mensual</p>
                        <p class="mt-2 text-xl font-black text-gray-800">
                            ${{ number_format($prestamo->pago_mensual, 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Tasa</p>
                        <p class="mt-2 text-xl font-black text-gray-800">
                            {{ number_format($prestamo->tasa_interes, 2) }}%
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Plazo</p>
                        <p class="mt-2 text-xl font-black text-gray-800">
                            {{ $prestamo->plazo_meses }} meses
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Inicio</p>
                        <p class="mt-2 text-xl font-black text-gray-800">
                            {{ \Carbon\Carbon::parse($prestamo->fecha_inicio)->format('d/m/Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Final</p>
                        <p class="mt-2 text-xl font-black text-gray-800">
                            {{ \Carbon\Carbon::parse($prestamo->fecha_final)->format('d/m/Y') }}
                        </p>
                    </div>

                </div>

            </details>

            {{-- PAGOS --}}
            <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                    <h2 class="text-3xl font-black text-gray-800">
                        Pagos registrados
                    </h2>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[900px]">

                        <thead>

                            <tr class="bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 text-white">

                                <th class="p-5 text-left">Folio</th>
                                <th class="p-5 text-center">Fecha</th>
                                <th class="p-5 text-right">Monto</th>
                                <th class="p-5 text-center">Método</th>
                                <th class="p-5 text-center">Estado</th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($pagos as $pago)

                                <tr class="transition duration-300 hover:bg-purple-50">

                                    <td class="p-5 font-semibold text-gray-800">
                                        {{ $pago->folio_pago }}
                                    </td>

                                    <td class="p-5 text-center">
                                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                                    </td>

                                    <td class="p-5 text-right font-black text-green-600">
                                        ${{ number_format($pago->monto, 2) }}
                                    </td>

                                    <td class="p-5 text-center">
                                        {{ $pago->metodo_pago }}
                                    </td>

                                    <td class="p-5 text-center">

                                        <span class="rounded-full bg-green-100 px-4 py-2 text-xs font-bold text-green-700">
                                            {{ $pago->estado_label }}
                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="p-10 text-center text-gray-500">
                                        No hay pagos registrados para este préstamo.
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

    </div>

</x-app-layout>