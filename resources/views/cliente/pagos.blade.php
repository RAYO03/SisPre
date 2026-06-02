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

            {{-- ENCABEZADO --}}
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                        Historial financiero
                    </span>

                    <h1 class="mt-4 text-4xl font-black text-purple-700">
                        Mis Pagos
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Consulta tus pagos registrados y administra tus préstamos activos.
                    </p>
                </div>

                @if(($prestamosActivos ?? collect())->count() === 1)
                    <a href="{{ route('cliente.pagos.create', $prestamosActivos->first()->id) }}"
                       class="rounded-2xl bg-gradient-to-r from-purple-600 to-fuchsia-600 px-6 py-3 text-center text-sm font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105 hover:from-purple-700 hover:to-fuchsia-700">
                        Registrar pago →
                    </a>
                @endif

            </div>

            {{-- MENSAJE SUCCESS --}}
            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- PRESTAMOS ACTIVOS --}}
            @if(($prestamosActivos ?? collect())->count() > 1)
                <div class="mb-8 rounded-3xl border border-gray-200 bg-white p-6 shadow-xl">
                    <div class="mb-5 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-fuchsia-600 text-xl text-white shadow-lg shadow-purple-300 animate-float">
                            💳
                        </div>

                        <div>
                            <h2 class="text-xl font-black text-gray-800">
                                Registrar pago por préstamo
                            </h2>

                            <p class="text-sm text-gray-500">
                                Selecciona el préstamo al que deseas registrar un pago.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @foreach($prestamosActivos as $prestamo)
                            <a href="{{ route('cliente.pagos.create', $prestamo->id) }}"
                               class="rounded-2xl border border-purple-200 bg-purple-50 px-4 py-3 text-sm font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-100">
                                {{ $prestamo->folio }} -
                                ${{ number_format($prestamo->saldo_pendiente, 2) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- TABLA --}}
            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                <div class="flex flex-col gap-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 p-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-black text-gray-800">
                            Pagos registrados
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Historial de movimientos realizados en tu cuenta.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-purple-200 bg-white px-4 py-2 text-sm font-semibold text-purple-700 shadow-sm">
                        Total: {{ ($pagos ?? collect())->count() }}
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px]">
                        <thead class="bg-purple-700 text-white">
                            <tr>
                                <th class="p-4 text-left text-sm font-bold">Folio</th>
                                <th class="p-4 text-left text-sm font-bold">Monto</th>
                                <th class="p-4 text-left text-sm font-bold">Método</th>
                                <th class="p-4 text-left text-sm font-bold">Fecha</th>
                                <th class="p-4 text-left text-sm font-bold">Estado</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($pagos ?? [] as $pago)
                                <tr class="transition hover:bg-purple-50/60">
                                    <td class="p-4 font-semibold text-gray-700">
                                        {{ $pago->folio_pago }}
                                    </td>

                                    <td class="p-4 font-bold text-gray-800">
                                        ${{ number_format($pago->monto, 2) }}
                                    </td>

                                    <td class="p-4 text-gray-600">
                                        {{ $pago->metodo_pago }}
                                    </td>

                                    <td class="p-4 text-gray-600">
                                        {{ $pago->fecha_pago }}
                                    </td>

                                    <td class="p-4">
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                            {{ $pago->estado_label }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-10 text-center">
                                        <div class="mx-auto flex max-w-sm flex-col items-center">
                                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-purple-50 text-3xl">
                                                💸
                                            </div>

                                            <h3 class="text-lg font-black text-gray-800">
                                                No tienes pagos registrados
                                            </h3>

                                            <p class="mt-2 text-sm text-gray-500">
                                                Cuando realices un pago, aparecerá aquí en tu historial.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pagos->hasPages())
                    <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                        {{ $pagos->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>
</x-app-layout>