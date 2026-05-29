<x-app-layout>
<div class="p-8">
    <h1 class="text-3xl font-bold text-purple-700 mb-6">Solicitudes</h1>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-purple-700 text-white">
                <tr>
                    <th class="p-4 text-left">Folio</th>
                    <th class="p-4 text-left">Cliente</th>
                    <th class="p-4 text-left">Monto</th>
                    <th class="p-4 text-left">Estado</th>
                    <th class="p-4 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($solicitudes ?? [] as $solicitud)
                    <tr class="border-b">
                        <td class="p-4">{{ $solicitud->folio }}</td>
                        <td class="p-4">{{ $solicitud->user->name ?? 'Sin cliente' }}</td>
                        <td class="p-4">${{ number_format($solicitud->monto_solicitado, 2) }}</td>
                        <td class="p-4">{{ ucfirst($solicitud->estado) }}</td>
                        <td class="p-4 flex gap-2">
                            <form method="POST" action="{{ route('admin.solicitudes.aprobar', $solicitud->id) }}">
                                @csrf
                                <button class="bg-green-600 text-white px-3 py-1 rounded">Aprobar</button>
                            </form>

                            <form method="POST" action="{{ route('admin.solicitudes.rechazar', $solicitud->id) }}">
                                @csrf
                                <button class="bg-red-600 text-white px-3 py-1 rounded">Rechazar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">
                            No hay solicitudes registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>