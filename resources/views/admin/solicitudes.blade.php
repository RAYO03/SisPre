<x-app-layout>
<div class="p-8">
    <h1 class="text-3xl font-bold text-purple-700 mb-6">Solicitudes</h1>

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

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
                        <td class="p-4">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold
                                @if($solicitud->estado === 'pendiente') bg-yellow-100 text-yellow-700
                                @elseif($solicitud->estado === 'aprobada') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($solicitud->estado) }}
                            </span>
                        </td>
                        <td class="p-4">
                            @if($solicitud->estado === 'pendiente')
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('admin.solicitudes.aprobar', $solicitud->id) }}"
                                        onsubmit="return confirm('¿Seguro que deseas aprobar esta solicitud? Se generará un préstamo para el cliente.');">
                                        @csrf
                                        <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                            Aprobar 
                                        </button>
                                    </form>

                                    <form method="POST"
                                          action="{{ route('admin.solicitudes.rechazar', $solicitud->id) }}"
                                          onsubmit="return confirm('¿Seguro que deseas rechazar esta solicitud? Esta accion no se puede deshacer.');">
                                        @csrf
                                        <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                            Rechazar
                                        </button>
                                    </form>
                                </div>
                            @elseif($solicitud->estado === 'aprobada')
                                <span class="text-sm font-medium text-green-700">
                                    Aprobada, préstamo generado
                                </span>
                            @else
                                <span class="text-sm font-medium text-red-700">
                                    Rechazada, sin acciones pendientes
                                </span>
                            @endif
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
