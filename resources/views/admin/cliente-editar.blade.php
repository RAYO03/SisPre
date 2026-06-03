<x-app-layout>
    <div class="min-h-screen bg-slate-100 px-4 py-10">

        <div class="mx-auto max-w-4xl">

            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                        Editar cliente
                    </span>

                    <h1 class="mt-4 text-4xl font-black text-purple-700">
                        Modificar Cliente
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Actualiza la información registrada del cliente.
                    </p>
                </div>

                <a href="{{ route('admin.clientes.show', $user->id) }}"
                   class="rounded-2xl border border-purple-200 bg-white px-5 py-3 text-sm font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-50">
                    ← Volver
                </a>
            </div>

            <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-2xl">

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.clientes.update', $user->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div>
                            <label class="mb-2 block text-sm font-bold text-gray-700">
                                Nombre
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-gray-700">
                                Correo
                            </label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-gray-700">
                                Teléfono
                            </label>
                            <input type="text"
                                   name="telefono"
                                   value="{{ old('telefono', $cliente->telefono ?? '') }}"
                                   class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-gray-700">
                                Fecha de nacimiento
                            </label>
                            <input type="date"
                                   name="fecha_nacimiento"
                                   value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento ?? '') }}"
                                   class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-bold text-gray-700">
                                Dirección
                            </label>
                            <input type="text"
                                   name="direccion"
                                   value="{{ old('direccion', $cliente->direccion ?? '') }}"
                                   class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-gray-700">
                                Ciudad
                            </label>
                            <input type="text"
                                   name="ciudad"
                                   value="{{ old('ciudad', $cliente->ciudad ?? '') }}"
                                   class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-gray-700">
                                Estado
                            </label>
                            <input type="text"
                                   name="estado"
                                   value="{{ old('estado', $cliente->estado ?? '') }}"
                                   class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>

                    </div>

                    <div class="flex flex-col gap-3 pt-4 sm:flex-row sm:justify-end">

                        <a href="{{ route('admin.clientes.show', $user->id) }}"
                           class="rounded-2xl border border-gray-300 bg-white px-6 py-3 text-center text-sm font-bold text-gray-600 transition hover:bg-gray-100">
                            Cancelar
                        </a>

                        <button type="submit"
                                class="rounded-2xl bg-purple-700 px-6 py-3 text-sm font-bold text-white shadow transition hover:scale-105 hover:bg-purple-800">
                            Guardar cambios
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</x-app-layout>