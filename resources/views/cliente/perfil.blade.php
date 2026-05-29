<x-app-layout>
    <div class="p-8">
        <div class="mx-auto max-w-4xl">
            <h1 class="mb-6 text-3xl font-bold text-purple-700">Mi Perfil</h1>

            @if(session('success'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-2xl bg-white shadow">
                <div class="flex items-center justify-between border-b px-6 py-5">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>

                    <a href="{{ route('cliente.perfil.edit') }}"
                       class="rounded bg-purple-700 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-800">
                        Editar perfil
                    </a>
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
