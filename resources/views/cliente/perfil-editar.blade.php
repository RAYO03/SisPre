<x-app-layout>
    <div class="p-8">
        <div class="mx-auto max-w-4xl">
            <h1 class="mb-6 text-3xl font-bold text-purple-700">Editar Perfil</h1>

            <form method="POST"
                  action="{{ route('cliente.perfil.update') }}"
                  class="overflow-hidden rounded-2xl bg-white shadow">
                @csrf
                @method('PATCH')

                <div class="flex items-center justify-between border-b px-6 py-5">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>

                    <a href="{{ route('cliente.perfil') }}"
                       class="text-sm font-semibold text-purple-700 hover:text-purple-900 hover:underline">
                        Volver al perfil
                    </a>
                </div>

                <div class="grid grid-cols-1 divide-y md:grid-cols-2 md:divide-x md:divide-y-0">
                    <div class="space-y-5 p-6">
                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-400">
                                Nombre
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-400">
                                Correo
                            </label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-400">
                                Telefono
                            </label>
                            <input type="tel"
                                   name="telefono"
                                   value="{{ old('telefono', $cliente->telefono ?? '') }}"
                                   maxlength="10"
                                   pattern="[0-9]{10}"
                                   class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-400">
                                Fecha de nacimiento
                            </label>
                            <input type="date"
                                   name="fecha_nacimiento"
                                   value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento ?? '') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
                        </div>
                    </div>

                    <div class="space-y-5 p-6">
                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-400">
                                Direccion
                            </label>
                            <input type="text"
                                   name="direccion"
                                   value="{{ old('direccion', $cliente->direccion ?? '') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-400">
                                    Ciudad
                                </label>
                                <input type="text"
                                       name="ciudad"
                                       value="{{ old('ciudad', $cliente->ciudad ?? '') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                                <x-input-error :messages="$errors->get('ciudad')" class="mt-2" />
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-400">
                                    Estado
                                </label>
                                <input type="text"
                                       name="estado"
                                       value="{{ old('estado', $cliente->estado ?? '') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                                <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t px-6 py-5">
                    <a href="{{ route('cliente.perfil') }}"
                       class="rounded border border-gray-200 px-5 py-3 text-gray-600 hover:bg-gray-50">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="rounded bg-purple-700 px-6 py-3 font-semibold text-white hover:bg-purple-800">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
