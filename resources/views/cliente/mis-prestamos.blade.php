<x-app-layout>

<div class="p-8">

    <h1 class="text-3xl font-bold text-purple-700 mb-6">
        Mis Préstamos
    </h1>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-purple-600 text-white">

                <tr>
                    <th class="p-4 text-left">Folio</th>
                    <th class="p-4 text-left">Monto</th>
                    <th class="p-4 text-left">Plazo</th>
                    <th class="p-4 text-left">Estado</th>
                    <th class="p-4 text-left">Acción</th>
                </tr>

            </thead>

            <tbody>

                @forelse($prestamos ?? [] as $prestamo)

                <tr class="border-b">

                    <td class="p-4">
                        CRD-{{ $prestamo->id }}
                    </td>

                    <td class="p-4">
                        ${{ number_format($prestamo->capital,2) }}
                    </td>

                    <td class="p-4">
                        {{ $prestamo->plazo_meses }} meses
                    </td>

                    <td class="p-4">
                        {{ ucfirst($prestamo->estado) }}
                    </td>

                    <td class="p-4">

                        <a href="{{ route('cliente.estado-cuenta',$prestamo->id) }}"
                           class="bg-purple-600 text-white px-4 py-2 rounded">
                            Ver
                        </a>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="5" class="p-6 text-center">
                        No tienes préstamos registrados
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</x-app-layout>