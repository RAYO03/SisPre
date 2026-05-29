<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Dashboard Cliente
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-7xl mx-auto px-6">

            <h1 class="text-3xl font-bold text-purple-700 mb-6">
                Hola, {{ auth()->user()->name }} 👋
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Prestamo -->
                <div class="bg-white rounded-2xl shadow p-6">

                    <p class="text-gray-500">
                        Préstamo Activo
                    </p>

                    @if($prestamoActivo)

                        <h2 class="text-3xl font-bold text-purple-700 mt-2">
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

                <!-- Solicitudes -->
                <div class="bg-white rounded-2xl shadow p-6">

                    <p class="text-gray-500">
                        Solicitudes
                    </p>

                    <h2 class="text-3xl font-bold text-blue-600 mt-2">
                        {{ $solicitudes->count() }}
                    </h2>

                    <p class="text-gray-500 mt-2">
                        Solicitudes registradas
                    </p>

                </div>

                <!-- Pagos -->
                <div class="bg-white rounded-2xl shadow p-6">

                    <p class="text-gray-500">
                        Pagos
                    </p>

                    <h2 class="text-3xl font-bold text-green-600 mt-2">
                        {{ $pagos->count() }}
                    </h2>

                    <p class="text-gray-500 mt-2">
                        Pagos realizados
                    </p>

                </div>

            </div>

            <!-- Acciones -->
            <div class="mt-8 flex flex-wrap gap-4">

                <a href="{{ route('cliente.simulador') }}"
                   class="bg-purple-700 hover:bg-purple-800 text-white px-6 py-3 rounded-xl font-semibold">
                    Simular préstamo
                </a>

                <a href="{{ route('cliente.solicitud') }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold">
                    Solicitar préstamo
                </a>

                <a href="{{ route('cliente.prestamos') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold">
                    Mis préstamos
                </a>

                <a href="{{ route('cliente.pagos') }}"
                   class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl font-semibold">
                    Pagos
                </a>

            </div>

            <!-- Tabla -->
            <div class="mt-10 bg-white rounded-2xl shadow">

                <div class="p-6 border-b">

                    <h2 class="text-xl font-bold">
                        Últimas solicitudes
                    </h2>

                </div>

                <table class="w-full">

                    <thead class="bg-gray-50">

                        <tr>
                            <th class="p-4 text-left">Folio</th>
                            <th class="p-4 text-left">Monto</th>
                            <th class="p-4 text-left">Estado</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($solicitudes as $solicitud)

                            <tr class="border-t">

                                <td class="p-4">
                                    {{ $solicitud->folio }}
                                </td>

                                <td class="p-4">
                                    ${{ number_format($solicitud->monto_solicitado,2) }}
                                </td>

                                <td class="p-4">

                                    @if($solicitud->estado == 'aprobada')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                            Aprobada
                                        </span>
                                    @elseif($solicitud->estado == 'rechazada')
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                            Rechazada
                                        </span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">
                                            Pendiente
                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="p-6 text-center text-gray-500">
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