<x-app-layout>
    <div class="p-8">
        <h1 class="mb-6 text-3xl font-bold text-purple-700">Clientes</h1>

        <div class="overflow-hidden rounded-2xl bg-white shadow">
            <table class="w-full">
                <thead class="bg-purple-700 text-white">
                    <tr>
                        <th class="p-4 text-left">Nombre</th>
                        <th class="p-4 text-left">Correo</th>
                        <th class="p-4 text-left">Telefono</th>
                        <th class="p-4 text-center">Accion</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($clientes ?? [] as $cliente)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium text-gray-800">{{ $cliente->name ?? 'Sin nombre' }}</td>
                            <td class="p-4 text-gray-600">{{ $cliente->email ?? 'Sin correo' }}</td>
                            <td class="p-4">
                                {{ $cliente->cliente->telefono ?? 'Perfil incompleto' }}
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('admin.clientes.show', $cliente->id) }}"
                                   class="rounded bg-purple-700 px-4 py-2 text-white hover:bg-purple-800">
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
