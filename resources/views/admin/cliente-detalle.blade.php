<x-app-layout>
    <div class="p-8">
        <div class="mx-auto max-w-4xl">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-purple-700">Detalle del Cliente</h1>

                <a href="{{ route('admin.clientes') }}"
                   class="text-sm font-semibold text-purple-700 hover:text-purple-900 hover:underline">
                    Volver a clientes
                </a>
            </div>

            <div class="overflow-hidden rounded-2xl bg-white shadow">
                <div class="border-b px-6 py-5">
                    <h2 class="text-xl font-bold text-gray-800">{{ $user->name ?? 'N/A' }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->email ?? 'N/A' }}</p>
                </div>

                <div class="grid grid-cols-1 divide-y md:grid-cols-2 md:divide-x md:divide-y-0">
                    <div class="space-y-5 p-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Telefono</p>
                            <p class="mt-1 text-base font-semibold text-gray-800">{{ $cliente->telefono ?? 'Sin telefono' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Fecha de nacimiento</p>
                            <p class="mt-1 text-base font-semibold text-gray-800">
                                {{ $cliente?->fecha_nacimiento ? \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') : 'Sin fecha' }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5 p-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Direccion</p>
                            <p class="mt-1 text-base font-semibold text-gray-800">{{ $cliente->direccion ?? 'Sin direccion' }}</p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Ciudad</p>
                                <p class="mt-1 text-base font-semibold text-gray-800">{{ $cliente->ciudad ?? 'Sin ciudad' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Estado</p>
                                <p class="mt-1 text-base font-semibold text-gray-800">{{ $cliente->estado ?? 'Sin estado' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
