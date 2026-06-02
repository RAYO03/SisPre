<x-app-layout>
    <div class="p-8">
        <h1 class="mb-6 text-3xl font-bold text-purple-700">
            Mis Solicitudes
        </h1>

        <div class="overflow-hidden rounded-xl bg-white shadow">
            <table class="w-full">
                <thead class="bg-purple-600 text-white">
                    <tr>
                        <th class="p-4 text-left">Folio</th>
                        <th class="p-4 text-left">Monto</th>
                        <th class="p-4 text-left">Fecha</th>
                        <th class="p-4 text-center">Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($solicitudes ?? [] as $solicitud)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium text-gray-800">
                                {{ $solicitud->folio ?? 'CRD-' . $solicitud->id }}
                            </td>

                            <td class="p-4 font-semibold text-green-700">
                                ${{ number_format($solicitud->monto_solicitado, 2) }}
                            </td>

                            <td class="p-4 text-gray-600">
                                {{ $solicitud->created_at?->format('d/m/Y') }}
                            </td>

                            <td class="p-4 text-center">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold
                                    @if($solicitud->estado === \App\Support\Estado::SOLICITADO) bg-yellow-100 text-yellow-700
                                    @elseif($solicitud->estado === \App\Support\Estado::APROBADO) bg-green-100 text-green-700
                                    @elseif($solicitud->estado === \App\Support\Estado::RECHAZADO) bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $solicitud->estado_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">
                                No hay solicitudes registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
