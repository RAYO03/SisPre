<x-app-layout>

<div class="p-8">

    <h1 class="text-3xl font-bold text-purple-700 mb-6">
        Mis Solicitudes
    </h1>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-purple-600 text-white">

                <tr>
                    <th class="p-4">Folio</th>
                    <th class="p-4">Monto</th>
                    <th class="p-4">Fecha</th>
                    <th class="p-4">Estado</th>
                </tr>

            </thead>

            <tbody>

                @forelse($solicitudes ?? [] as $solicitud)

                <tr class="border-b">

                    <td class="p-4">
                        CRD-{{ $solicitud->id }}
                    </td>

                    <td class="p-4">
                        ${{ number_format($solicitud->monto_solicitado,2) }}
                    </td>

                    <td class="p-4">
                        {{ $solicitud->created_at }}
                    </td>

                    <td class="p-4">
                        {{ ucfirst($solicitud->estado) }}
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="4" class="text-center p-6">
                        No hay solicitudes registradas
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</x-app-layout>