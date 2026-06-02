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

            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                        Editar información
                    </span>

                    <h1 class="mt-4 text-4xl font-black text-purple-700">
                        Editar Perfil
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Actualiza tu información personal registrada en Credify.
                    </p>
                </div>

                <a href="{{ route('cliente.perfil') }}"
                   class="rounded-2xl border border-purple-200 bg-white px-6 py-3 text-center text-sm font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-50">
                    ← Volver al perfil
                </a>
            </div>

            <form method="POST"
                  action="{{ route('cliente.perfil.update') }}"
                  class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">
                @csrf
                @method('PATCH')

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
                        Modo edición
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-0 md:grid-cols-2">

                    <div class="space-y-5 border-b border-gray-200 p-6 md:border-b-0 md:border-r">
                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-purple-600">
                                Nombre
                            </label>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-800 shadow-sm transition focus:border-purple-500 focus:ring-purple-500">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-purple-600">
                                Correo
                            </label>

                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-800 shadow-sm transition focus:border-purple-500 focus:ring-purple-500">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-purple-600">
                                Teléfono
                            </label>

                            <input type="tel"
                                   name="telefono"
                                   value="{{ old('telefono', $cliente->telefono ?? '') }}"
                                   maxlength="10"
                                   pattern="[0-9]{10}"
                                   class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-800 shadow-sm transition focus:border-purple-500 focus:ring-purple-500">
                            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-purple-600">
                                Fecha de nacimiento
                            </label>

                            <input type="date"
                                   name="fecha_nacimiento"
                                   value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento ?? '') }}"
                                   min="1900-01-01"
                                   required
                                   class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-800 shadow-sm transition focus:border-purple-500 focus:ring-purple-500">

                            <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
                        </div>
                    </div>

                    <div class="space-y-5 p-6">
                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-purple-600">
                                Dirección
                            </label>

                            <input type="text"
                                   name="direccion"
                                   value="{{ old('direccion', $cliente->direccion ?? '') }}"
                                   class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-800 shadow-sm transition focus:border-purple-500 focus:ring-purple-500">
                            <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-purple-600">
                                    Ciudad
                                </label>

                                <input type="text"
                                       name="ciudad"
                                       value="{{ old('ciudad', $cliente->ciudad ?? '') }}"
                                       class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-800 shadow-sm transition focus:border-purple-500 focus:ring-purple-500">
                                <x-input-error :messages="$errors->get('ciudad')" class="mt-2" />
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-purple-600">
                                    Estado
                                </label>

                                <input type="text"
                                       name="estado"
                                       value="{{ old('estado', $cliente->estado ?? '') }}"
                                       class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-800 shadow-sm transition focus:border-purple-500 focus:ring-purple-500">
                                <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex flex-col gap-3 border-t border-gray-200 bg-gray-50 px-6 py-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('cliente.perfil') }}"
                       class="rounded-2xl border border-gray-200 bg-white px-5 py-3 text-center font-semibold text-gray-600 transition hover:bg-gray-100">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="rounded-2xl bg-gradient-to-r from-purple-600 to-fuchsia-600 px-6 py-3 font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105 hover:from-purple-700 hover:to-fuchsia-700">
                        Guardar cambios
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>