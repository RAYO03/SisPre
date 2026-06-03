<x-app-layout>

    <style>
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeUp .8s ease-out forwards;
        }
    </style>

    <div class="min-h-screen bg-slate-100 py-8">

        <div class="max-w-7xl mx-auto px-6 animate-fade-up">

            {{-- ENCABEZADO --}}
            <div class="mb-8">

                <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                    Panel del cliente
                </span>

                <h1 class="mt-4 text-4xl font-black text-purple-700">
                    Hola, {{ auth()->user()->name }} 👋
                </h1>

                <p class="mt-2 text-gray-500">
                    Bienvenido a Credify. Administra tus préstamos, pagos y solicitudes desde un solo lugar.
                </p>

            </div>

            {{-- TARJETAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- PRESTAMO --}}
                <div class="bg-white rounded-3xl shadow-xl p-6 transition hover:-translate-y-1 hover:shadow-2xl">

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-100 text-2xl mb-4">
                        💰
                    </div>

                    <p class="text-gray-500 font-medium">
                        Préstamo Activo
                    </p>

                    @if($prestamoActivo)

                        <h2 class="text-3xl font-black text-purple-700 mt-2">
                            ${{ number_format($prestamoActivo->monto_total,2) }}
                        </h2>

                        <p class="text-gray-500 mt-2">
                            Saldo:
                            ${{ number_format($prestamoActivo->saldo_pendiente,2) }}
                        </p>

                    @else

                        <h2 class="text-xl font-semibold text-gray-600 mt-2">
                            Sin préstamo activo
                        </h2>

                    @endif

                </div>

                {{-- SOLICITUDES --}}
                <div class="bg-white rounded-3xl shadow-xl p-6 transition hover:-translate-y-1 hover:shadow-2xl">

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-2xl mb-4">
                        📋
                    </div>

                    <p class="text-gray-500 font-medium">
                        Solicitudes
                    </p>

                    <h2 class="text-3xl font-black text-blue-600 mt-2">
                        {{ $solicitudes->count() }}
                    </h2>

                    <p class="text-gray-500 mt-2">
                        Solicitudes registradas
                    </p>

                </div>

                {{-- PAGOS --}}
                <div class="bg-white rounded-3xl shadow-xl p-6 transition hover:-translate-y-1 hover:shadow-2xl">

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-2xl mb-4">
                        💳
                    </div>

                    <p class="text-gray-500 font-medium">
                        Pagos
                    </p>

                    <h2 class="text-3xl font-black text-green-600 mt-2">
                        {{ $pagos->count() }}
                    </h2>

                    <p class="text-gray-500 mt-2">
                        Pagos realizados
                    </p>

                </div>

            </div>

            {{-- BOTONES CENTRADOS --}}
            <div class="mt-10 flex flex-wrap justify-center gap-4">

                <a href="{{ route('cliente.simulador') }}"
                   class="rounded-2xl bg-gradient-to-r from-purple-600 to-fuchsia-600 px-6 py-3 font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105 hover:from-purple-700 hover:to-fuchsia-700">
                    Simular préstamo
                </a>

                <a href="{{ route('cliente.solicitud') }}"
                   class="rounded-2xl bg-green-600 px-6 py-3 font-bold text-white shadow-lg shadow-green-300 transition hover:scale-105 hover:bg-green-700">
                    Solicitar préstamo
                </a>

                <a href="{{ route('cliente.prestamos') }}"
                   class="rounded-2xl bg-blue-600 px-6 py-3 font-bold text-white shadow-lg shadow-blue-300 transition hover:scale-105 hover:bg-blue-700">
                    Mis préstamos
                </a>

                <a href="{{ route('cliente.pagos') }}"
                   class="rounded-2xl bg-orange-500 px-6 py-3 font-bold text-white shadow-lg shadow-orange-300 transition hover:scale-105 hover:bg-orange-600">
                    Pagos
                </a>

            </div>

            {{-- TABLA --}}
            <div class="mt-10 overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="border-b bg-gradient-to-r from-purple-50 to-fuchsia-50 p-6">

                    <h2 class="text-2xl font-black text-gray-800">
                        Últimas solicitudes
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Seguimiento de tus solicitudes más recientes.
                    </p>

                </div>

                <table class="w-full">

                    <thead class="bg-purple-700 text-white">

                        <tr>
                            <th class="p-4 text-left">Folio</th>
                            <th class="p-4 text-left">Monto</th>
                            <th class="p-4 text-left">Estado</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($solicitudes as $solicitud)

                            <tr class="border-t hover:bg-purple-50 transition">

                                <td class="p-4 font-semibold">
                                    {{ $solicitud->folio }}
                                </td>

                                <td class="p-4 font-bold text-green-700">
                                    ${{ number_format($solicitud->monto_solicitado,2) }}
                                </td>

                                <td class="p-4">

                                    @if($solicitud->estado == \App\Support\Estado::APROBADO)
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                            {{ $solicitud->estado_label }}
                                        </span>
                                    @elseif($solicitud->estado == \App\Support\Estado::RECHAZADO)
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">
                                            {{ $solicitud->estado_label }}
                                        </span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">
                                            {{ $solicitud->estado_label }}
                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="p-8 text-center text-gray-500">
                                    No hay solicitudes registradas.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>