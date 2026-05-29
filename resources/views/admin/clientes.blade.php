<x-app-layout>
<div class="p-8">
    <h1 class="text-3xl font-bold text-purple-700 mb-6">Clientes</h1>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-purple-700 text-white">
                <tr>
                    <th class="p-4 text-left">Nombre</th>
                    <th class="p-4 text-left">Correo</th>
                    <th class="p-4 text-left">Teléfono</th>
                    <th class="p-4 text-left">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes ?? [] as $cliente)
                    <tr class="border-b">
                        <td class="p-4">{{ $cliente->user->name ?? 'Sin nombre' }}</td>
                        <td class="p-4">{{ $cliente->user->email ?? 'Sin correo' }}</td>
                        <td class="p-4">{{ $cliente->telefono ?? 'N/A' }}</td>
                        <td class="p-4">
                            <a href="{{ route('admin.clientes.show', $cliente->id) }}"
                               class="bg-purple-700 text-white px-4 py-2 rounded">
                                Ver
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-500">
                            No hay clientes registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-app-layout> 