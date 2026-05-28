<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Mis pagos
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-purple-700 text-white">

                            <tr>
                                <th class="px-6 py-4 text-left">ID Pago</th>
                                <th class="px-6 py-4 text-left">Préstamo</th>
                                <th class="px-6 py-4 text-left">Monto</th>
                                <th class="px-6 py-4 text-left">Fecha</th>
                                <th class="px-6 py-4 text-left">Método</th>
                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">

                            @forelse($pagos as $pago)

                                <tr class="hover:bg-gray-50 transition">

                                    <td class="px-6 py-4 font-semibold text-gray-700">
                                        #{{ $pago->id }}
                                    </td>

                                    <td class="px-6 py-4">
                                        #{{ $pago->prestamo_id }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-green-700">
                                        ${{ number_format($pago->monto, 2) }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $pago->fecha_pago }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $pago->metodo_pago ?? 'No definido' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        No tienes pagos registrados.
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