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

        .animate-slide-up {
            animation: slideUp .8s ease-out forwards;
        }
    </style>

    <div class="min-h-screen bg-slate-100 px-4 py-10">

        <div class="mx-auto max-w-5xl animate-slide-up">

            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                        Editar préstamo
                    </span>

                    <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                        Modificar Préstamo
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Actualiza la información financiera del préstamo.
                    </p>
                </div>

                <a href="{{ route('admin.prestamos.show', $prestamo->id) }}"
                   class="rounded-2xl border border-purple-300 bg-white px-5 py-3 text-sm font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-100">
                    ← Volver
                </a>

            </div>

            <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Información del préstamo
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Modifica los datos necesarios y guarda los cambios.
                    </p>
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

                    <form action="{{ route('admin.prestamos.update', $prestamo->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                            <div>
                                <label class="mb-2 block text-sm font-bold text-purple-700">
                                    Cliente
                                </label>

                                <select name="user_id"
                                        class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}"
                                            @selected(old('user_id', $prestamo->user_id) == $cliente->id)>
                                            {{ $cliente->name }} - {{ $cliente->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-purple-700">
                                    Estado
                                </label>

                                <select name="estado"
                                        class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    <option value="{{ \App\Support\Estado::ACTIVO }}"
                                        @selected(old('estado', $prestamo->estado) == \App\Support\Estado::ACTIVO)>
                                        Activo
                                    </option>

                                    <option value="{{ \App\Support\Estado::EN_MORA }}"
                                        @selected(old('estado', $prestamo->estado) == \App\Support\Estado::EN_MORA)>
                                        En mora
                                    </option>

                                    <option value="{{ \App\Support\Estado::LIQUIDADO }}"
                                        @selected(old('estado', $prestamo->estado) == \App\Support\Estado::LIQUIDADO)>
                                        Liquidado
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-purple-700">
                                    Monto original
                                </label>

                                <input type="number"
                                       step="0.01"
                                       min="{{ $montoMinimo }}"
                                       max="{{ $montoMaximo }}"
                                       name="monto_original"
                                       value="{{ old('monto_original', $prestamo->monto_original) }}"
                                       class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">

                                <p class="mt-2 text-xs text-gray-500">
                                    Rango permitido:
                                    ${{ number_format($montoMinimo, 2) }}
                                    -
                                    ${{ number_format($montoMaximo, 2) }}
                                </p>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-purple-700">
                                    Plazo en meses
                                </label>

                                <select name="plazo_meses"
                                        class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    @foreach ($plazos as $plazo)
                                        <option value="{{ $plazo }}"
                                            @selected(old('plazo_meses', $prestamo->plazo_meses) == $plazo)>
                                            {{ $plazo }} meses
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-purple-700">
                                    Fecha de inicio
                                </label>

                                <input type="date"
                                       name="fecha_inicio"
                                       value="{{ old('fecha_inicio', $prestamo->fecha_inicio) }}"
                                       class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-purple-700">
                                    Folio
                                </label>

                                <input type="text"
                                       value="{{ $prestamo->folio }}"
                                       disabled
                                       class="w-full rounded-2xl border-gray-200 bg-gray-100 text-gray-500 shadow-sm">
                            </div>

                        </div>

                        <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-4 text-sm font-semibold text-yellow-800">
                            Al guardar cambios se recalculará el monto total, pago mensual, fecha final y tabla de amortización.
                        </div>

                        <div class="flex flex-col gap-3 pt-4 sm:flex-row sm:justify-end">

                            <a href="{{ route('admin.prestamos.show', $prestamo->id) }}"
                               class="rounded-2xl border border-gray-300 bg-white px-6 py-3 text-center text-sm font-bold text-gray-600 transition hover:bg-gray-100">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-6 py-3 text-sm font-bold text-white shadow-xl shadow-purple-400 transition hover:scale-105">
                                Guardar cambios
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
</x-app-layout>