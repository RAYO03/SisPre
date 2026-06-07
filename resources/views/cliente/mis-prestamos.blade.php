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

            <div class="mb-8">

                <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                    Préstamos del cliente
                </span>

                <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                    Mis Préstamos
                </h1>

                <p class="mt-2 text-gray-500">
                    Consulta tus préstamos, saldos, plazos y estado actual.
                </p>

            </div>

            <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                    <div class="flex items-center gap-4">

                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-600 to-fuchsia-600 text-3xl text-white shadow-xl shadow-purple-300 animate-float">
                            💳
                        </div>

                        <div>

                            <h2 class="text-3xl font-black text-gray-800">
                                Lista de préstamos
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Historial de préstamos registrados en tu cuenta.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[1050px]">

                        <thead>

                            <tr class="bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 text-white">
                                <th class="p-5 text-left font-bold">Folio</th>
                                <th class="p-5 text-left font-bold">Monto solicitado</th>
                                <th class="p-5 text-left font-bold">Total a pagar</th>
                                <th class="p-5 text-left font-bold">Saldo</th>
                                <th class="p-5 text-left font-bold">Plazo</th>
                                <th class="p-5 text-left font-bold">Estado</th>
                                <th class="p-5 text-left font-bold">Acciones</th>
                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($prestamos ?? [] as $prestamo)

                                <tr class="transition duration-300 hover:bg-purple-50 hover:shadow-lg">

                                    <td class="p-5 font-black text-gray-800">
                                        {{ $prestamo->folio }}
                                    </td>

                                    <td class="p-5 font-bold text-gray-800">
                                        ${{ number_format($prestamo->monto_original ?? $prestamo->solicitud?->monto_solicitado ?? $prestamo->monto_total, 2) }}
                                    </td>

                                    <td class="p-5 font-semibold text-gray-700">
                                        ${{ number_format($prestamo->monto_total, 2) }}
                                    </td>

                                    <td class="p-5 text-lg font-black text-green-600">
                                        ${{ number_format($prestamo->saldo_pendiente, 2) }}
                                    </td>

                                    <td class="p-5 font-semibold text-gray-700">
                                        {{ $prestamo->plazo_meses }} meses
                                    </td>

                                    <td class="p-5">

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

                                        <div class="flex flex-wrap gap-3">

                                            <a href="{{ route('cliente.estado-cuenta', $prestamo->id) }}"
                                               class="rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105">
                                                Ver
                                            </a>

                                            @if(in_array($prestamo->estado, [\App\Support\Estado::ACTIVO, \App\Support\Estado::EN_MORA], true))

                                                <a href="{{ route('cliente.pagos.create', $prestamo->id) }}"
                                                   class="rounded-2xl bg-gradient-to-r from-green-600 to-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-green-300 transition hover:scale-105">
                                                    Pagar
                                                </a>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="p-14 text-center">

                                        <div class="mx-auto flex max-w-md flex-col items-center">

                                            <div class="mb-5 flex h-24 w-24 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-100 to-fuchsia-100 text-5xl animate-float">
                                                💳
                                            </div>

                                            <h3 class="text-2xl font-black text-gray-800">
                                                No tienes préstamos registrados
                                            </h3>

                                            <p class="mt-3 text-gray-500">
                                                Cuando tengas un préstamo aprobado, aparecerá aquí.
                                            </p>

                                        </div>

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