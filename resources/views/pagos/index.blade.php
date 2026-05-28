<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Pagos de Clientes
                </h1>
            </div>

            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <table class="w-full text-sm">

                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left text-gray-600 font-medium">
                                #
                            </th>

                            <th class="px-4 py-3 text-left text-gray-600 font-medium">
                                Cliente
                            </th>

                            <th class="px-4 py-3 text-left text-gray-600 font-medium">
                                Préstamo
                            </th>

                            <th class="px-4 py-3 text-right text-gray-600 font-medium">
                                Monto
                            </th>

                            <th class="px-4 py-3 text-left text-gray-600 font-medium">
                                Fecha
                            </th>

                            <th class="px-4 py-3 text-center text-gray-600 font-medium">
                                Estado
                            </th>

                            <th class="px-4 py-3 text-center text-gray-600 font-medium">
                                Acciones
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($pagos as $pago)

                            <tr class="hover:bg-gray-50">

                                <td class="px-4 py-3 text-gray-400">
                                    {{ $pago->id }}
                                </td>

                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $pago->prestamo->cliente->name ?? 'Sin cliente' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    #{{ $pago->prestamo_id }}
                                </td>

                                <td class="px-4 py-3 text-right font-medium text-green-700">
                                    ${{ number_format($pago->monto, 2) }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y') : 'Sin fecha' }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-medium">
                                        Pagado
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-3">

                                        <a href="{{ route('pagos.show', $pago) }}"
                                           class="text-indigo-600 hover:underline">
                                            Ver
                                        </a>

                                        <a href="{{ route('prestamos.show', $pago->prestamo_id) }}"
                                           class="text-gray-500 hover:underline">
                                            Ver Préstamo
                                        </a>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="px-4 py-10 text-center text-gray-400">
                                    No hay pagos registrados.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            <div class="mt-4">
                {{ $pagos->links() }}
            </div>

        </div>
    </div>
</x-app-layout>