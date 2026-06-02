<x-app-layout>
    <div class="p-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-purple-700">
                Prestamos activos
            </h1>

            <a href="{{ route('admin.prestamos.create') }}"
               class="rounded bg-purple-700 px-5 py-3 font-semibold text-white hover:bg-purple-800">
                Crear prestamo
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-purple-700 text-white">
                    <tr>
                        <th class="p-4 text-left">Cliente</th>
                        <th class="p-4 text-right">Monto original</th>
                        <th class="p-4 text-right">Monto total</th>
                        <th class="p-4 text-right">Saldo</th>
                        <th class="p-4 text-right">Pago mensual</th>
                        <th class="p-4 text-center">Plazo</th>
                        <th class="p-4 text-center">Estado</th>
                        <th class="p-4 text-center">Accion</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($prestamos ?? [] as $prestamo)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium text-gray-800">
                                {{ $prestamo->user->name ?? 'N/A' }}
                            </td>

                            <td class="p-4 text-right">
                                ${{ number_format($prestamo->monto_original ?? $prestamo->solicitud?->monto_solicitado ?? $prestamo->monto_total, 2) }}
                            </td>

                            <td class="p-4 text-right">
                                ${{ number_format($prestamo->monto_total, 2) }}
                            </td>

                            <td class="p-4 text-right font-semibold text-green-700">
                                ${{ number_format($prestamo->saldo_pendiente, 2) }}
                            </td>

                            <td class="p-4 text-right">
                                ${{ number_format($prestamo->pago_mensual, 2) }}
                            </td>

                            <td class="p-4 text-center">
                                {{ $prestamo->plazo_meses }} meses
                            </td>

                            <td class="p-4 text-center">
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    {{ $prestamo->estado_label }}
                                </span>
                            </td>

                            <td class="p-4 text-center">
                                <a href="{{ route('admin.prestamos.show', $prestamo->id) }}"
                                   class="rounded bg-purple-700 px-4 py-2 text-white hover:bg-purple-800">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-gray-500">
                                No hay prestamos activos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
