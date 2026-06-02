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

            {{-- ENCABEZADO --}}
            <div class="mb-8">
                <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                    Solicitudes de crédito
                </span>

                <h1 class="mt-4 text-4xl font-black text-purple-700">
                    Mis Solicitudes
                </h1>

                <p class="mt-2 text-gray-500">
                    Consulta el estado actual de todas tus solicitudes de préstamo.
                </p>
            </div>

            {{-- TABLA --}}
            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Historial de solicitudes
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Seguimiento de solicitudes enviadas a Credify.
                    </p>
                </div>

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[800px] text-sm">

                        <thead class="bg-purple-700 text-white">
                            <tr>
                                <th class="p-4 text-left">Folio</th>
                                <th class="p-4 text-left">Monto solicitado</th>
                                <th class="p-4 text-left">Fecha</th>
                                <th class="p-4 text-center">Estado</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($solicitudes ?? [] as $solicitud)

                                <tr class="transition hover:bg-purple-50/60">

                                    <td class="p-4 font-bold text-gray-800">
                                        {{ $solicitud->folio ?? 'CRD-' . $solicitud->id }}
                                    </td>

                                    <td class="p-4">
                                        <span class="font-bold text-green-700">
                                            ${{ number_format($solicitud->monto_solicitado, 2) }}
                                        </span>
                                    </td>

                                    <td class="p-4 text-gray-600">
                                        {{ $solicitud->created_at?->format('d/m/Y') }}
                                    </td>

                                    <td class="p-4 text-center">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold
                                            @if($solicitud->estado === \App\Support\Estado::SOLICITADO)
                                                bg-yellow-100 text-yellow-700
                                            @elseif($solicitud->estado === \App\Support\Estado::APROBADO)
                                                bg-green-100 text-green-700
                                            @elseif($solicitud->estado === \App\Support\Estado::RECHAZADO)
                                                bg-red-100 text-red-700
                                            @else
                                                bg-gray-100 text-gray-700
                                            @endif">

                                            {{ $solicitud->estado_label }}

                                        </span>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="p-10 text-center">

                                        <div class="mx-auto flex max-w-sm flex-col items-center">

                                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-purple-50 text-3xl">
                                                📋
                                            </div>

                                            <h3 class="text-lg font-black text-gray-800">
                                                No tienes solicitudes registradas
                                            </h3>

                                            <p class="mt-2 text-sm text-gray-500">
                                                Cuando envíes una solicitud de préstamo,
                                                aparecerá aquí para que puedas darle seguimiento.
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