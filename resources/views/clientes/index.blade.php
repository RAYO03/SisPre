<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Clientes 
                </h1>
            </div>

            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left text-gray-600 font-medium">
                                Nombre
                            </th>

                            <th class="px-4 py-3 text-left text-gray-600 font-medium">
                                Email
                            </th>

                            <th class="px-4 py-3 text-left text-gray-600 font-medium">
                                Fecha de Registro
                            </th>

                            <th class="px-4 py-3 text-center text-gray-600 font-medium">
                                Acciones
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($clientes as $cliente)
                            <tr class="hover:bg-gray-50">

                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $cliente->name }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $cliente->email }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $cliente->created_at->format('d/m/Y') }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('clientes.show', $cliente->id) }}"
                                       class="text-indigo-600 hover:underline">
                                        Ver
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-gray-400">
                                    No hay clientes registrados aún.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $clientes->links() }}
            </div>

        </div>
    </div>
</x-app-layout>