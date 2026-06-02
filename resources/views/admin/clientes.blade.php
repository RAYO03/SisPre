<x-app-layout>
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-slide-up {
            animation: slideUp .8s ease-out forwards;
        }
    </style>

    <div class="min-h-screen bg-slate-100 px-4 py-10">
        <div class="mx-auto max-w-7xl animate-slide-up">

            {{-- ENCABEZADO --}}
            <div class="mb-8">
                <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                    Administración de clientes
                </span>

                <h1 class="mt-4 text-4xl font-black text-purple-700">
                    Clientes
                </h1>

                <p class="mt-2 text-gray-500">
                    Consulta la información de todos los clientes registrados en Credify.
                </p>
            </div>

            {{-- TABLA --}}
            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Lista de clientes
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Usuarios registrados dentro del sistema.
                    </p>
                </div>

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[900px] text-sm">

                        <thead class="bg-purple-700 text-white">
                            <tr>
                                <th class="p-4 text-left">Nombre</th>
                                <th class="p-4 text-left">Correo</th>
                                <th class="p-4 text-left">Teléfono</th>
                                <th class="p-4 text-center">Acción</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($clientes ?? [] as $cliente)

                                <tr class="transition hover:bg-purple-50/60">

                                    <td class="p-4">
                                        <div class="flex items-center gap-3">

                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-fuchsia-600 font-bold text-white">
                                                {{ strtoupper(substr($cliente->name ?? 'C', 0, 1)) }}
                                            </div>

                                            <span class="font-bold text-gray-800">
                                                {{ $cliente->name ?? 'Sin nombre' }}
                                            </span>

                                        </div>
                                    </td>

                                    <td class="p-4 text-gray-600">
                                        {{ $cliente->email ?? 'Sin correo' }}
                                    </td>

                                    <td class="p-4 text-gray-700">
                                        {{ $cliente->cliente->telefono ?? 'Perfil incompleto' }}
                                    </td>

                                    <td class="p-4 text-center">
                                        <a href="{{ route('admin.clientes.show', $cliente->id) }}"
                                           class="inline-flex items-center rounded-xl bg-purple-700 px-4 py-2 text-sm font-bold text-white transition hover:scale-105 hover:bg-purple-800">
                                            Ver perfil
                                        </a>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="p-10 text-center">

                                        <div class="mx-auto flex max-w-sm flex-col items-center">

                                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-purple-50 text-3xl">
                                                👥
                                            </div>

                                            <h3 class="text-lg font-black text-gray-800">
                                                No hay clientes registrados
                                            </h3>

                                            <p class="mt-2 text-sm text-gray-500">
                                                Los clientes aparecerán aquí cuando creen una cuenta.
                                            </p>

                                        </div>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>