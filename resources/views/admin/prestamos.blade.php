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
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div>
                    <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                        Administración de préstamos
                    </span>

                    <h1 class="mt-4 text-4xl font-black text-purple-700">
                        Préstamos Activos
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Consulta y administra todos los préstamos registrados en el sistema.
                    </p>
                </div>

                <a href="{{ route('admin.prestamos.create') }}"
                   class="rounded-2xl bg-gradient-to-r from-purple-600 to-fuchsia-600 px-6 py-3 font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105 hover:from-purple-700 hover:to-fuchsia-700">
                    + Crear préstamo
                </a>

            </div>

            {{-- TABLA --}}
            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Lista de préstamos
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Seguimiento de préstamos activos y su estado financiero.
                    </p>
                </div>

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[1200px] text-sm">

                        <thead class="bg-purple-700 text-white">
                            <tr>
                                <th class="p-4 text-left">Cliente</th>
                                <th class="p-4 text-right">Monto original</th>
                                <th class="p-4 text-right">Monto total</th>
                                <th class="p-4 text-right">Saldo</th>
                                <th class="p-4 text-right">Pago mensual</th>
                                <th class="p-4 text-center">Plazo</th>
                                <th class="p-4 text-center">Estado</th>
                                <th class="p-4 text-center">Acción</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($prestamos ?? [] as $prestamo)

                                <tr class="transition hover:bg-purple-50/60">

                                    <td class="p-4">
                                        <div class="flex items-center gap-3">

                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-fuchsia-600 font-bold text-white">
                                                {{ strtoupper(substr($prestamo->user->name ?? 'U', 0, 1)) }}
                                            </div>

                                            <span class="font-bold text-gray-800">
                                                {{ $prestamo->user->name ?? 'N/A' }}
                                            </span>

                                        </div>
                                    </td>

                                    <td class="p-4 text-right font-semibold text-gray-800">
                                        ${{ number_format($prestamo->monto_original ?? $prestamo->solicitud?->monto_solicitado ?? $prestamo->monto_total, 2) }}
                                    </td>

                                    <td class="p-4 text-right text-gray-700">
                                        ${{ number_format($prestamo->monto_total, 2) }}
                                    </td>

                                    <td class="p-4 text-right font-bold text-green-700">
                                        ${{ number_format($prestamo->saldo_pendiente, 2) }}
                                    </td>

                                    <td class="p-4 text-right text-gray-700">
                                        ${{ number_format($prestamo->pago_mensual, 2) }}
                                    </td>

                                    <td class="p-4 text-center text-gray-700">
                                        {{ $prestamo->plazo_meses }} meses
                                    </td>

                                    <td class="p-4 text-center">

                                        @if($prestamo->estado === \App\Support\Estado::ACTIVO)
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                                {{ $prestamo->estado_label }}
                                            </span>

                                        @elseif($prestamo->estado === \App\Support\Estado::EN_MORA)
                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                                {{ $prestamo->estado_label }}
                                            </span>

                                        @elseif($prestamo->estado === \App\Support\Estado::LIQUIDADO)
                                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700">
                                                {{ $prestamo->estado_label }}
                                            </span>

                                        @else
                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-700">
                                                {{ $prestamo->estado_label }}
                                            </span>
                                        @endif

                                    </td>

                                    <td class="p-4 text-center">

                                        <a href="{{ route('admin.prestamos.show', $prestamo->id) }}"
                                           class="inline-flex items-center rounded-xl bg-purple-700 px-4 py-2 text-sm font-bold text-white transition hover:scale-105 hover:bg-purple-800">
                                            Ver detalle
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8" class="p-10 text-center">

                                        <div class="mx-auto flex max-w-sm flex-col items-center">

                                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-purple-50 text-3xl">
                                                💰
                                            </div>

                                            <h3 class="text-lg font-black text-gray-800">
                                                No hay préstamos activos
                                            </h3>

                                            <p class="mt-2 text-sm text-gray-500">
                                                Los préstamos creados aparecerán aquí automáticamente.
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