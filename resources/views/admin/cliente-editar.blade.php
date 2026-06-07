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
            0%,100% { transform: translateY(0px); }
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

        <div class="mx-auto max-w-4xl animate-slide-up">

            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                        Editar cliente
                    </span>

                    <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                        Modificar Cliente
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Actualiza la información registrada del cliente.
                    </p>
                </div>

                <a href="{{ route('admin.clientes.show', $user->id) }}"
                   class="rounded-2xl border border-purple-300 bg-white px-5 py-3 text-sm font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-100">
                    ← Volver
                </a>
            </div>

            <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="flex items-center gap-5 border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 p-8">

                    <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-600 to-fuchsia-600 text-3xl font-black text-white shadow-xl shadow-purple-300 animate-float">
                        ✏️
                    </div>

                    <div>
                        <h2 class="text-3xl font-black text-gray-800">
                            Editar información
                        </h2>

                        <p class="mt-1 text-gray-500">
                            Modifica los datos y guarda los cambios.
                        </p>
                    </div>

                </div>

                <div class="p-8">

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

                            <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                                <label class="mb-2 block text-sm font-bold text-gray-700">
                                    Nombre
                                </label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                       class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>

                            <div class="rounded-3xl border-t-4 border-blue-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                                <label class="mb-2 block text-sm font-bold text-gray-700">
                                    Correo
                                </label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>

                            <div class="rounded-3xl border-t-4 border-green-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                                <label class="mb-2 block text-sm font-bold text-gray-700">
                                    Teléfono
                                </label>
                                <input type="text"
                                       name="telefono"
                                       value="{{ old('telefono', $cliente->telefono ?? '') }}"
                                       maxlength="10"
                                       pattern="[0-9]{10}"
                                       required
                                       class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>

                            <div class="rounded-3xl border-t-4 border-fuchsia-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                                <label class="mb-2 block text-sm font-bold text-gray-700">
                                    Fecha de nacimiento
                                </label>
                                <input type="date"
                                       name="fecha_nacimiento"
                                       id="fecha_nacimiento"
                                       min="1900-01-01"
                                       max="{{ now()->subYears(18)->toDateString() }}"
                                       value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento ?? '') }}"
                                       class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>

                            <div class="rounded-3xl border-t-4 border-indigo-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl md:col-span-2">
                                <label class="mb-2 block text-sm font-bold text-gray-700">
                                    Dirección
                                </label>
                                <input type="text"
                                       name="direccion"
                                       value="{{ old('direccion', $cliente->direccion ?? '') }}"
                                       class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>

                            <div class="rounded-3xl border-t-4 border-cyan-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                                <label class="mb-2 block text-sm font-bold text-gray-700">
                                    Ciudad
                                </label>
                                <input type="text"
                                       name="ciudad"
                                       value="{{ old('ciudad', $cliente->ciudad ?? '') }}"
                                       class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>

                            <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
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
                               class="rounded-2xl border border-gray-300 bg-white px-6 py-3 text-center text-sm font-bold text-gray-600 transition hover:scale-105 hover:bg-gray-100">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-6 py-3 text-sm font-bold text-white shadow-lg transition hover:scale-105">
                                Guardar cambios
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <script>
        const fechaNacimiento = document.getElementById('fecha_nacimiento');

        fechaNacimiento?.addEventListener('invalid', () => {
            if (fechaNacimiento.validity.rangeOverflow) {
                fechaNacimiento.setCustomValidity('Solo se aceptan fechas de nacimiento de usuarios mayores de edad.');
                return;
            }

            if (fechaNacimiento.validity.rangeUnderflow || fechaNacimiento.validity.badInput) {
                fechaNacimiento.setCustomValidity('Ingresa una fecha de nacimiento valida.');
                return;
            }

            fechaNacimiento.setCustomValidity('');
        });

        fechaNacimiento?.addEventListener('input', () => {
            fechaNacimiento.setCustomValidity('');
        });
    </script>

</x-app-layout>
