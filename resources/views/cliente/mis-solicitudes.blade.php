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
            <div class="mb-8">

                <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                    Solicitudes de crédito
                </span>

                <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                    Mis Solicitudes
                </h1>

                <p class="mt-2 text-gray-500">
                    Consulta el estado actual de todas tus solicitudes de préstamo.
                </p>

            </div>

            {{-- TABLA --}}
            <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                {{-- CABECERA --}}
                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                    <div class="flex items-center gap-4">

                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-600 to-fuchsia-600 text-3xl text-white shadow-xl shadow-purple-300 animate-float">
                            📋
                        </div>

                        <div>

                            <h2 class="text-3xl font-black text-gray-800">
                                Historial de solicitudes
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Seguimiento de solicitudes enviadas a Credify.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[900px]">

                        <thead>

                            <tr class="bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 text-white">

                                <th class="p-5 text-left font-bold">
                                    Folio
                                </th>

                                <th class="p-5 text-left font-bold">
                                    Monto solicitado
                                </th>

                                <th class="p-5 text-left font-bold">
                                    Fecha
                                </th>

                                <th class="p-5 text-center font-bold">
                                    Estado
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($solicitudes ?? [] as $solicitud)

                                <tr class="transition duration-300 hover:bg-purple-50 hover:shadow-lg">

                                    <td class="p-5 font-black text-gray-800">
                                        {{ $solicitud->folio ?? 'CRD-' . $solicitud->id }}
                                    </td>

                                    <td class="p-5">

                                        <span class="text-lg font-black text-green-600">
                                            ${{ number_format($solicitud->monto_solicitado, 2) }}
                                        </span>

                                    </td>

                                    <td class="p-5 font-semibold text-gray-600">
                                        {{ $solicitud->created_at?->format('d/m/Y') }}
                                    </td>

                                    <td class="p-5 text-center">

                                        <span class="rounded-full px-4 py-2 text-xs font-bold

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

                                    <td colspan="4" class="p-14 text-center">

                                        <div class="mx-auto flex max-w-md flex-col items-center">

                                            <div class="mb-5 flex h-24 w-24 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-100 to-fuchsia-100 text-5xl animate-float">
                                                📋
                                            </div>

                                            <h3 class="text-2xl font-black text-gray-800">
                                                No tienes solicitudes registradas
                                            </h3>

                                            <p class="mt-3 text-gray-500">
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