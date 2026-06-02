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
                    Administración
                </span>

                <h1 class="mt-4 text-4xl font-black text-purple-700">
                    Solicitudes
                </h1>

                <p class="mt-2 text-gray-500">
                    Revisa, aprueba o rechaza las solicitudes de préstamo enviadas por los clientes.
                </p>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Lista de solicitudes
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Solicitudes registradas en el sistema Credify.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-sm">
                        <thead class="bg-purple-700 text-white">
                            <tr>
                                <th class="p-4 text-left">Folio</th>
                                <th class="p-4 text-left">Cliente</th>
                                <th class="p-4 text-left">Monto</th>
                                <th class="p-4 text-left">Estado</th>
                                <th class="p-4 text-left">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($solicitudes ?? [] as $solicitud)
                                <tr class="transition hover:bg-purple-50/60">
                                    <td class="p-4 font-bold text-gray-800">
                                        {{ $solicitud->folio }}
                                    </td>

                                    <td class="p-4 text-gray-700">
                                        {{ $solicitud->user->name ?? 'Sin cliente' }}
                                    </td>

                                    <td class="p-4 font-bold text-green-700">
                                        ${{ number_format($solicitud->monto_solicitado, 2) }}
                                    </td>

                                    <td class="p-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold
                                            @if($solicitud->estado === \App\Support\Estado::SOLICITADO)
                                                bg-yellow-100 text-yellow-700
                                            @elseif($solicitud->estado === \App\Support\Estado::APROBADO)
                                                bg-green-100 text-green-700
                                            @else
                                                bg-red-100 text-red-700
                                            @endif">
                                            {{ $solicitud->estado_label }}
                                        </span>
                                    </td>

                                    <td class="p-4">
                                        @if($solicitud->estado === \App\Support\Estado::SOLICITADO)
                                            <div class="flex flex-wrap gap-2">
                                                <form method="POST"
                                                      action="{{ route('admin.solicitudes.aprobar', $solicitud->id) }}"
                                                      onsubmit="return confirm('¿Seguro que deseas aprobar esta solicitud? Se generará un préstamo para el cliente.');">
                                                    @csrf

                                                    <button class="rounded-xl bg-green-600 px-4 py-2 text-sm font-bold text-white transition hover:scale-105 hover:bg-green-700">
                                                        Aprobar
                                                    </button>
                                                </form>

                                                <form method="POST"
                                                      action="{{ route('admin.solicitudes.rechazar', $solicitud->id) }}"
                                                      onsubmit="return confirm('¿Seguro que deseas rechazar esta solicitud? Esta acción no se puede deshacer.');">
                                                    @csrf

                                                    <button class="rounded-xl bg-red-600 px-4 py-2 text-sm font-bold text-white transition hover:scale-105 hover:bg-red-700">
                                                        Rechazar
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($solicitud->estado === \App\Support\Estado::APROBADO)
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                                Aprobado, préstamo generado
                                            </span>
                                        @else
                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                                Rechazado, sin acciones pendientes
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-10 text-center">
                                        <div class="mx-auto flex max-w-sm flex-col items-center">
                                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-purple-50 text-3xl">
                                                📋
                                            </div>

                                            <h3 class="text-lg font-black text-gray-800">
                                                No hay solicitudes registradas
                                            </h3>

                                            <p class="mt-2 text-sm text-gray-500">
                                                Cuando un cliente envíe una solicitud, aparecerá aquí.
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