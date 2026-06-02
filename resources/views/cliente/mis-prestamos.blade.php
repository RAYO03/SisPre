<x-app-layout>
    <div class="p-8">
        <h1 class="mb-6 text-3xl font-bold text-purple-700">
            Mis Prestamos
        </h1>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-purple-600 text-white">
                    <tr>
                        <th class="p-4 text-left">Folio</th>
                        <th class="p-4 text-left">Monto solicitado</th>
                        <th class="p-4 text-left">Total a pagar</th>
                        <th class="p-4 text-left">Saldo</th>
                        <th class="p-4 text-left">Plazo</th>
                        <th class="p-4 text-left">Estado</th>
                        <th class="p-4 text-left">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($prestamos ?? [] as $prestamo)
                        <tr class="border-b">
                            <td class="p-4">{{ $prestamo->folio }}</td>

                            <td class="p-4">
                                <span class="font-semibold text-gray-800">
                                    ${{ number_format($prestamo->monto_original ?? $prestamo->solicitud?->monto_solicitado ?? $prestamo->monto_total, 2) }}
                                </span>
                            </td>

                            <td class="p-4">
                                ${{ number_format($prestamo->monto_total, 2) }}
                            </td>

                            <td class="p-4 font-semibold text-green-700">
                                ${{ number_format($prestamo->saldo_pendiente, 2) }}
                            </td>

                            <td class="p-4">
                                {{ $prestamo->plazo_meses }} meses
                            </td>

                            <td class="p-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold
                                    @if($prestamo->estado === \App\Support\Estado::ACTIVO) bg-green-100 text-green-700
                                    @elseif($prestamo->estado === \App\Support\Estado::LIQUIDADO) bg-blue-100 text-blue-700
                                    @elseif($prestamo->estado === \App\Support\Estado::EN_MORA) bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $prestamo->estado_label }}
                                </span>
                            </td>

                            <td class="p-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('cliente.estado-cuenta', $prestamo->id) }}"
                                       class="rounded bg-purple-700 px-4 py-2 text-white hover:bg-purple-800">
                                        Ver
                                    </a>

                                    @if(in_array($prestamo->estado, [\App\Support\Estado::ACTIVO, \App\Support\Estado::EN_MORA], true))
                                        <a href="{{ route('cliente.pagos.create', $prestamo->id) }}"
                                           class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                            Pagar
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center">
                                No tienes prestamos registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
