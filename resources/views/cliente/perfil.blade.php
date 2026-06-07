<x-app-layout>

    <style>
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%,100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-8px);
            }
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
                    <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                        Perfil del cliente
                    </span>

                    <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                        Mi Perfil
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Consulta tu información personal registrada en Credify.
                    </p>
                </div>

                <a href="{{ route('cliente.perfil.edit') }}"
                   class="rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-6 py-3 text-center text-sm font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105">
                    Editar perfil →
                </a>

            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-bold text-green-700 shadow-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- TARJETA PRINCIPAL --}}
            <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                {{-- ENCABEZADO --}}
                <div class="flex flex-col gap-5 border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 p-8 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-5">

                        <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-600 to-fuchsia-600 text-3xl font-black text-white shadow-xl shadow-purple-300 animate-float">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <div>
                            <h2 class="text-3xl font-black text-gray-800">
                                {{ $user->name }}
                            </h2>

                            <p class="mt-1 text-gray-500">
                                {{ $user->email }}
                            </p>
                        </div>

                    </div>

                    <div class="rounded-2xl border border-purple-300 bg-white px-5 py-3 text-sm font-bold text-purple-700 shadow-sm">
                        Cuenta activa
                    </div>

                </div>

                {{-- DATOS --}}
                <div class="p-8">

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                            <div class="mb-4 text-3xl animate-float">
                                📱
                            </div>

                            <p class="text-sm font-semibold text-gray-500">
                                Teléfono
                            </p>

                            <h3 class="mt-3 text-xl font-black text-gray-800">
                                {{ $cliente->telefono ?? 'Sin teléfono' }}
                            </h3>

                        </div>

                        <div class="rounded-3xl border-t-4 border-blue-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                            <div class="mb-4 text-3xl animate-float">
                                🎂
                            </div>

                            <p class="text-sm font-semibold text-gray-500">
                                Fecha de nacimiento
                            </p>

                            <h3 class="mt-3 text-xl font-black text-gray-800">
                                {{ $cliente?->fecha_nacimiento ? \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') : 'Sin fecha' }}
                            </h3>

                        </div>

                        <div class="rounded-3xl border-t-4 border-green-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl md:col-span-2">

                            <div class="mb-4 text-3xl animate-float">
                                📍
                            </div>

                            <p class="text-sm font-semibold text-gray-500">
                                Dirección
                            </p>

                            <h3 class="mt-3 text-xl font-black text-gray-800">
                                {{ $cliente->direccion ?? 'Sin dirección' }}
                            </h3>

                        </div>

                        <div class="rounded-3xl border-t-4 border-fuchsia-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                            <div class="mb-4 text-3xl animate-float">
                                🏙️
                            </div>

                            <p class="text-sm font-semibold text-gray-500">
                                Ciudad
                            </p>

                            <h3 class="mt-3 text-xl font-black text-gray-800">
                                {{ $cliente->ciudad ?? 'Sin ciudad' }}
                            </h3>

                        </div>

                        <div class="rounded-3xl border-t-4 border-indigo-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                            <div class="mb-4 text-3xl animate-float">
                                🌎
                            </div>

                            <p class="text-sm font-semibold text-gray-500">
                                Estado
                            </p>

                            <h3 class="mt-3 text-xl font-black text-gray-800">
                                {{ $cliente->estado ?? 'Sin estado' }}
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>