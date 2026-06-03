<x-app-layout>
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .animate-slide-up {
            animation: slideUp .8s ease-out forwards;
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
    </style>

    <div class="min-h-screen bg-slate-100 px-4 py-10">

        <div class="mx-auto max-w-5xl animate-slide-up">

            {{-- ENCABEZADO --}}
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                        Información del cliente
                    </span>

                    <h1 class="mt-4 text-4xl font-black text-purple-700">
                        Detalle del Cliente
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Consulta la información personal registrada por el cliente.
                    </p>
                </div>

                <a href="{{ route('admin.clientes') }}"
                   class="rounded-2xl border border-purple-200 bg-white px-5 py-3 text-sm font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-50">
                    ← Volver a clientes
                </a>

            </div>

            {{-- TARJETA PRINCIPAL --}}
            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                {{-- HEADER CLIENTE --}}
                <div class="flex flex-col gap-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 p-6 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-4">

                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-fuchsia-600 text-2xl font-black text-white shadow-lg shadow-purple-300 animate-float">
                            {{ strtoupper(substr($user->name ?? 'C', 0, 1)) }}
                        </div>

                        <div>
                            <h2 class="text-2xl font-black text-gray-800">
                                {{ $user->name ?? 'N/A' }}
                            </h2>

                            <p class="text-sm text-gray-500">
                                {{ $user->email ?? 'N/A' }}
                            </p>
                        </div>

                    </div>

                    <div class="flex flex-wrap gap-3">

                        <a href="{{ route('admin.clientes.edit', $user->id) }}"
                        class="rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow transition hover:scale-105 hover:bg-blue-700">
                            ✏️ Editar
                        </a>

                        <form action="{{ route('admin.clientes.destroy', $user->id) }}"
                            method="POST"
                            onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="rounded-2xl bg-red-600 px-5 py-3 text-sm font-bold text-white shadow transition hover:scale-105 hover:bg-red-700">
                                🗑️ Eliminar
                            </button>
                        </form>

                    </div>

                </div>

                {{-- DATOS --}}
                <div class="grid grid-cols-1 gap-0 md:grid-cols-2">

                    {{-- COLUMNA IZQUIERDA --}}
                    <div class="space-y-5 border-b border-gray-200 p-6 md:border-b-0 md:border-r">

                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-purple-300 hover:shadow-lg">
                            <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                                Teléfono
                            </p>

                            <p class="mt-2 text-lg font-bold text-gray-800">
                                {{ $cliente->telefono ?? 'Sin teléfono' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-purple-300 hover:shadow-lg">
                            <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                                Fecha de nacimiento
                            </p>

                            <p class="mt-2 text-lg font-bold text-gray-800">
                                {{ $cliente?->fecha_nacimiento ? \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') : 'Sin fecha' }}
                            </p>
                        </div>

                    </div>

                    {{-- COLUMNA DERECHA --}}
                    <div class="space-y-5 p-6">

                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-purple-300 hover:shadow-lg">
                            <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                                Dirección
                            </p>

                            <p class="mt-2 text-lg font-bold text-gray-800">
                                {{ $cliente->direccion ?? 'Sin dirección' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-purple-300 hover:shadow-lg">
                                <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                                    Ciudad
                                </p>

                                <p class="mt-2 text-lg font-bold text-gray-800">
                                    {{ $cliente->ciudad ?? 'Sin ciudad' }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-purple-300 hover:shadow-lg">
                                <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                                    Estado
                                </p>

                                <p class="mt-2 text-lg font-bold text-gray-800">
                                    {{ $cliente->estado ?? 'Sin estado' }}
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</x-app-layout>