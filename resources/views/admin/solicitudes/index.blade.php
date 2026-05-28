<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h1 class="text-2xl font-bold text-gray-800 mb-6">
                Solicitudes de Préstamos
            </h1>

            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left">Cliente</th>
                            <th class="px-4 py-3 text-right">Monto</th>
                            <th class="px-4 py-3 text-center">Plazo</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($solicitudes as $solicitud)

                            <tr>

                                <td class="px-4 py-3">
                                    {{ $solicitud->user->name ?? 'Sin usuario' }}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    ${{ number_format($solicitud->monto_solicitado, 2) }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $solicitud->plazo_meses }} meses
                                </td>

                                <td class="px-4 py-3 text-center">

                                    <span class="px-2 py-1 rounded-full text-xs font-medium

                                        @if($solicitud->estado === 'pendiente')
                                            bg-yellow-100 text-yellow-700

                                        @elseif($solicitud->estado === 'aprobado')
                                            bg-green-100 text-green-700

                                        @else
                                            bg-red-100 text-red-700
                                        @endif">

                                        {{ ucfirst($solicitud->estado) }}

                                    </span>

                                </td>

                                <td class="px-4 py-3 text-center">

                                    <a href="{{ route('admin.solicitudes.show', $solicitud) }}"
                                       class="text-indigo-600 hover:underline">

                                        Revisar

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5"
                                    class="px-4 py-10 text-center text-gray-400">

                                    No hay solicitudes registradas.

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-4">
                {{ $solicitudes->links() }}
            </div>

        </div>
    </div>
</x-app-layout>