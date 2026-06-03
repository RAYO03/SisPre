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
                    Panel del cliente
                </span>

                <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                    Hola, {{ auth()->user()->name }} 👋
                </h1>

                <p class="mt-2 text-gray-500">
                    Bienvenido a Credify. Administra tus préstamos, pagos y solicitudes desde un solo lugar.
                </p>

            </div>

            {{-- TARJETAS --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

                {{-- PRESTAMO --}}
                <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                    <div class="mb-4 text-4xl animate-float">
                        💰
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Préstamo Activo
                    </p>

                    @if($prestamoActivo)

                        <h2 class="mt-3 text-4xl font-black text-purple-700">
                            ${{ number_format($prestamoActivo->monto_total, 2) }}
                        </h2>

                        <p class="mt-2 font-semibold text-gray-500">
                            Saldo:
                            ${{ number_format($prestamoActivo->saldo_pendiente, 2) }}
                        </p>

                    @else

                        <h2 class="mt-3 text-xl font-black text-gray-600">
                            Sin préstamo activo
                        </h2>

                    @endif

                </div>

                {{-- SOLICITUDES --}}
                <div class="rounded-3xl border-t-4 border-blue-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                    <div class="mb-4 text-4xl animate-float">
                        📋
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Solicitudes
                    </p>

                    <h2 class="mt-3 text-4xl font-black text-blue-600">
                        {{ $solicitudes->count() }}
                    </h2>

                    <p class="mt-2 font-semibold text-gray-500">
                        Solicitudes registradas
                    </p>

                </div>

                {{-- PAGOS --}}
                <div class="rounded-3xl border-t-4 border-green-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                    <div class="mb-4 text-4xl animate-float">
                        💳
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Pagos
                    </p>

                    <h2 class="mt-3 text-4xl font-black text-green-600">
                        {{ $pagos->count() }}
                    </h2>

                    <p class="mt-2 font-semibold text-gray-500">
                        Pagos realizados
                    </p>

                </div>

            </div>

            {{-- BOTONES --}}
            <div class="mt-10 flex flex-wrap justify-center gap-4">

                <a href="{{ route('cliente.simulador') }}"
                   class="rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-6 py-3 font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105">
                    Simular préstamo
                </a>

                <a href="{{ route('cliente.solicitud') }}"
                   class="rounded-2xl bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-3 font-bold text-white shadow-lg shadow-green-300 transition hover:scale-105">
                    Solicitar préstamo
                </a>

                <a href="{{ route('cliente.prestamos') }}"
                   class="rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-3 font-bold text-white shadow-lg shadow-blue-300 transition hover:scale-105">
                    Mis préstamos
                </a>

                <a href="{{ route('cliente.pagos') }}"
                   class="rounded-2xl bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-3 font-bold text-white shadow-lg shadow-orange-300 transition hover:scale-105">
                    Pagos
                </a>

            </div>

            {{-- TABLA --}}
            <div class="mt-10 overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 p-6">

                    <div class="flex items-center gap-4">

                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-600 to-fuchsia-600 text-3xl text-white shadow-xl shadow-purple-300 animate-float">
                            📋
                        </div>

                        <div>
                            <h2 class="text-3xl font-black text-gray-800">
                                Últimas solicitudes
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Seguimiento de tus solicitudes más recientes.
                            </p>
                        </div>

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[800px]">

                        <thead>
                            <tr class="bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 text-white">
                                <th class="p-5 text-left font-bold">
                                    Folio
                                </th>
                                <th class="p-5 text-left font-bold">
                                    Monto
                                </th>
                                <th class="p-5 text-left font-bold">
                                    Estado
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($solicitudes as $solicitud)

                                <tr class="transition duration-300 hover:bg-purple-50 hover:shadow-lg">

                                    <td class="p-5 font-black text-gray-800">
                                        {{ $solicitud->folio }}
                                    </td>

                                    <td class="p-5 text-lg font-black text-green-600">
                                        ${{ number_format($solicitud->monto_solicitado, 2) }}
                                    </td>

                                    <td class="p-5">

                                        @if($solicitud->estado == \App\Support\Estado::APROBADO)

                                            <span class="rounded-full bg-green-100 px-4 py-2 text-xs font-bold text-green-700">
                                                {{ $solicitud->estado_label }}
                                            </span>

                                        @elseif($solicitud->estado == \App\Support\Estado::RECHAZADO)

                                            <span class="rounded-full bg-red-100 px-4 py-2 text-xs font-bold text-red-700">
                                                {{ $solicitud->estado_label }}
                                            </span>

                                        @else

                                            <span class="rounded-full bg-yellow-100 px-4 py-2 text-xs font-bold text-yellow-700">
                                                {{ $solicitud->estado_label }}
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="p-14 text-center">

                                        <div class="mx-auto flex max-w-md flex-col items-center">

                                            <div class="mb-5 flex h-24 w-24 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-100 to-fuchsia-100 text-5xl animate-float">
                                                📋
                                            </div>

                                            <h3 class="text-2xl font-black text-gray-800">
                                                No hay solicitudes registradas
                                            </h3>

                                            <p class="mt-3 text-gray-500">
                                                Cuando realices una solicitud, aparecerá aquí.
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