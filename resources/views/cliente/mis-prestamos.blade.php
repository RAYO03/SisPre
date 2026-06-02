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

            <div class="mb-8">
                <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                    Préstamos del cliente
                </span>

                <h1 class="mt-4 text-4xl font-black text-purple-700">
                    Mis Préstamos
                </h1>

                <p class="mt-2 text-gray-500">
                    Consulta tus préstamos, saldos, plazos y estado actual.
                </p>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Lista de préstamos
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Historial de préstamos registrados en tu cuenta.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[950px] text-sm">
                        <thead class="bg-purple-700 text-white">
                            <tr>
                                <th class="p-4 text-left">Folio</th>
                                <th class="p-4 text-left">Monto solicitado</th>
                                <th class="p-4 text-left">Total a pagar</th>
                                <th class="p-4 text-left">Saldo</th>
                                <th class="p-4 text-left">Plazo</th>
                                <th class="p-4 text-left">Estado</th>
                                <th class="p-4 text-left">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($prestamos ?? [] as $prestamo)
                                <tr class="transition hover:bg-purple-50/60">
                                    <td class="p-4 font-bold text-gray-800">
                                        {{ $prestamo->folio }}
                                    </td>

                                    <td class="p-4 font-semibold text-gray-800">
                                        ${{ number_format($prestamo->monto_original ?? $prestamo->solicitud?->monto_solicitado ?? $prestamo->monto_total, 2) }}
                                    </td>

                                    <td class="p-4 text-gray-700">
                                        ${{ number_format($prestamo->monto_total, 2) }}
                                    </td>

                                    <td class="p-4 font-bold text-green-700">
                                        ${{ number_format($prestamo->saldo_pendiente, 2) }}
                                    </td>

                                    <td class="p-4 text-gray-700">
                                        {{ $prestamo->plazo_meses }} meses
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
                                                <a href="{{ route('cliente.pagos.create', $prestamo->id) }}"
                                                   class="rounded-xl bg-green-600 px-4 py-2 text-sm font-bold text-white transition hover:scale-105 hover:bg-green-700">
                                                    Pagar
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-10 text-center">
                                        <div class="mx-auto flex max-w-sm flex-col items-center">
                                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-purple-50 text-3xl">
                                                💳
                                            </div>

                                            <h3 class="text-lg font-black text-gray-800">
                                                No tienes préstamos registrados
                                            </h3>

                                            <p class="mt-2 text-sm text-gray-500">
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