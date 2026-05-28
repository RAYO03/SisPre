<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Mis préstamos
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-purple-700 text-white">

                            <tr>
                                <th class="px-6 py-4 text-left">ID</th>
                                <th class="px-6 py-4 text-left">Capital</th>
                                <th class="px-6 py-4 text-left">Interés</th>
                                <th class="px-6 py-4 text-left">Plazo</th>
                                <th class="px-6 py-4 text-left">Cuota</th>
                                <th class="px-6 py-4 text-left">Estado</th>
                                <th class="px-6 py-4 text-left">Inicio</th>
                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">

                            @forelse($prestamos as $prestamo)

                                <tr class="hover:bg-gray-50 transition">

                                    <td class="px-6 py-4 font-semibold text-gray-700">
                                        #{{ $prestamo->id }}
                                    </td>

                                    <td class="px-6 py-4">
                                        ${{ number_format($prestamo->capital, 2) }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $prestamo->tasa_anual }}%
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $prestamo->plazo_meses }} meses
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-purple-700">
                                        ${{ number_format($prestamo->monto_cuota, 2) }}
                                    </td>

                                    <td class="px-6 py-4">

                                        @if($prestamo->estado === 'solicitado')
                                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                                Solicitado
                                            </span>
                                        @elseif($prestamo->estado === 'aprobado')
                                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                                Aprobado
                                            </span>
                                        @elseif($prestamo->estado === 'activo')
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                                Activo
                                            </span>
                                        @else
                                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                                {{ $prestamo->estado }}
                                            </span>
                                        @endif

                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $prestamo->fecha_inicio }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        No tienes préstamos registrados.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="p-6">
                    {{ $prestamos->links() }}
                </div>

            </div>

        </div>
    </div>

</x-app-layout>