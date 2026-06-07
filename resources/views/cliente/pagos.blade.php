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
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                        Historial financiero
                    </span>

                    <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                        Mis Pagos
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Consulta tus pagos registrados y administra tus préstamos activos.
                    </p>
                </div>

                @if(($prestamosActivos ?? collect())->count() === 1)
                    <a href="{{ route('cliente.pagos.create', $prestamosActivos->first()->id) }}"
                       class="rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-6 py-3 text-center text-sm font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105">
                        Registrar pago →
                    </a>
                @endif

            </div>

            {{-- MENSAJE SUCCESS --}}
            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-bold text-green-700 shadow-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- PRESTAMOS ACTIVOS --}}
            @if(($prestamosActivos ?? collect())->count() > 1)

                <div class="mb-8 overflow-hidden rounded-3xl bg-white shadow-2xl">

                    <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                        <div class="flex items-center gap-4">

                            <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-600 to-fuchsia-600 text-3xl text-white shadow-xl shadow-purple-300 animate-float">
                                💳
                            </div>

                            <div>
                                <h2 class="text-3xl font-black text-gray-800">
                                    Registrar pago por préstamo
                                </h2>

                                <p class="mt-1 text-sm text-gray-500">
                                    Selecciona el préstamo al que deseas registrar un pago.
                                </p>
                            </div>

                        </div>

                    </div>

                    <div class="flex flex-wrap gap-4 p-8">

                        @foreach($prestamosActivos as $prestamo)

                            <a href="{{ route('cliente.pagos.create', $prestamo->id) }}"
                               class="rounded-2xl border border-purple-300 bg-white px-5 py-4 text-sm font-bold text-purple-700 shadow-xl transition hover:-translate-y-1 hover:scale-105 hover:bg-purple-100 hover:shadow-2xl">
                                {{ $prestamo->folio }} -
                                ${{ number_format($prestamo->saldo_pendiente, 2) }}
                            </a>

                        @endforeach

                    </div>

                </div>

            @endif

            {{-- TABLA --}}
            <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex items-center gap-4">

                            <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-green-600 to-emerald-600 text-3xl text-white shadow-xl shadow-green-300 animate-float">
                                💸
                            </div>

                            <div>
                                <h2 class="text-3xl font-black text-gray-800">
                                    Pagos registrados
                                </h2>

                                <p class="mt-1 text-sm text-gray-500">
                                    Historial de movimientos realizados en tu cuenta.
                                </p>
                            </div>

                        </div>

                        <div class="rounded-2xl border border-purple-300 bg-white px-5 py-3 text-sm font-bold text-purple-700 shadow-sm">
                            Total: {{ $pagos->total() }}
                        </div>

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[850px]">

                        <thead>

                            <tr class="bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 text-white">

                                <th class="p-5 text-left font-bold">Folio</th>
                                <th class="p-5 text-left font-bold">Monto</th>
                                <th class="p-5 text-left font-bold">Método</th>
                                <th class="p-5 text-left font-bold">Fecha</th>
                                <th class="p-5 text-left font-bold">Estado</th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($pagos ?? [] as $pago)

                                <tr class="transition duration-300 hover:bg-purple-50 hover:shadow-lg">

                                    <td class="p-5 font-black text-gray-800">
                                        {{ $pago->folio_pago }}
                                    </td>

                                    <td class="p-5 text-lg font-black text-green-600">
                                        ${{ number_format($pago->monto, 2) }}
                                    </td>

                                    <td class="p-5 font-semibold text-gray-600">
                                        {{ $pago->metodo_pago }}
                                    </td>

                                    <td class="p-5 font-semibold text-gray-600">
                                        {{ $pago->fecha_pago }}
                                    </td>

                                    <td class="p-5">
                                        <span class="rounded-full bg-green-100 px-4 py-2 text-xs font-bold text-green-700">
                                            {{ $pago->estado_label }}
                                        </span>
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="p-14 text-center">

                                        <div class="mx-auto flex max-w-md flex-col items-center">

                                            <div class="mb-5 flex h-24 w-24 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-100 to-fuchsia-100 text-5xl animate-float">
                                                💸
                                            </div>

                                            <h3 class="text-2xl font-black text-gray-800">
                                                No tienes pagos registrados
                                            </h3>

                                            <p class="mt-3 text-gray-500">
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

                    <div class="border-t bg-gray-50 px-6 py-4">
                        {{ $pagos->links() }}
                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>
