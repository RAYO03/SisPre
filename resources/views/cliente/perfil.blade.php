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

            {{-- TITULO --}}
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                        Perfil del cliente
                    </span>

                    <h1 class="mt-4 text-4xl font-black text-purple-700">
                        Mi Perfil
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Consulta tu información personal registrada en Credify.
                    </p>
                </div>

                <a href="{{ route('cliente.perfil.edit') }}"
                   class="rounded-2xl bg-gradient-to-r from-purple-600 to-fuchsia-600 px-6 py-3 text-center text-sm font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105 hover:from-purple-700 hover:to-fuchsia-700">
                    Editar perfil →
                </a>

            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- TARJETA PRINCIPAL --}}
            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                {{-- ENCABEZADO --}}
                <div class="flex flex-col gap-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 p-6 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-4">

                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-fuchsia-600 text-2xl font-black text-white shadow-lg shadow-purple-300 animate-float">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <div>
                            <h2 class="text-2xl font-black text-gray-800">
                                {{ $user->name }}
                            </h2>

                            <p class="text-sm text-gray-500">
                                {{ $user->email }}
                            </p>
                        </div>

                    </div>

                    <div class="rounded-2xl border border-purple-200 bg-white px-4 py-2 text-sm font-semibold text-purple-700 shadow-sm">
                        Cuenta activa
                    </div>

                </div>

                {{-- DATOS --}}
                <div class="grid grid-cols-1 gap-0 md:grid-cols-2">

                    {{-- COLUMNA 1 --}}
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

                    {{-- COLUMNA 2 --}}
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