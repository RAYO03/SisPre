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

            {{-- ENCABEZADO --}}
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div>

                    <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                        Nueva solicitud
                    </span>

                    <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                        Solicitud de Préstamo
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Completa los datos para enviar tu solicitud a revisión.
                    </p>

                </div>

                <a href="{{ route('cliente.solicitudes') }}"
                   class="rounded-2xl border border-purple-300 bg-white px-5 py-3 text-sm font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-100">
                    Mis solicitudes
                </a>

            </div>

            <form method="POST"
                  action="{{ route('cliente.solicitud.store') }}"
                  class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                @csrf

                {{-- DATOS DEL PRESTAMO --}}
                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                    <div class="flex items-center gap-4">

                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-600 to-fuchsia-600 text-3xl text-white shadow-xl shadow-purple-300 animate-float">
                            💰
                        </div>

                        <div>

                            <h2 class="text-3xl font-black text-gray-800">
                                Datos del préstamo
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                El monto, plazo e ingreso ayudan a calcular tu pago mensual estimado.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="grid grid-cols-1 gap-6 p-8 md:grid-cols-2">

                    <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                        <label class="mb-3 block text-sm font-bold text-purple-700">
                            Monto solicitado
                        </label>

                        <input
                            name="monto_solicitado"
                            type="number"
                            min="{{ $montoMinimo }}"
                            max="{{ $montoMaximo }}"
                            step="0.01"
                            value="{{ old('monto_solicitado', request('monto_solicitado')) }}"
                            class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            required
                        >

                        <p class="mt-2 text-xs text-gray-500">
                            Rango permitido: ${{ number_format($montoMinimo, 2) }} a ${{ number_format($montoMaximo, 2) }}
                        </p>

                        <x-input-error :messages="$errors->get('monto_solicitado')" class="mt-2" />

                    </div>

                    <div class="rounded-3xl border-t-4 border-blue-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                        <label class="mb-3 block text-sm font-bold text-purple-700">
                            Plazo en meses
                        </label>

                        <select
                            name="plazo_meses"
                            class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            required
                        >

                            <option value="">Selecciona un plazo</option>

                            @foreach($plazos as $plazo)
                                <option value="{{ $plazo }}" @selected((string) old('plazo_meses', request('plazo_meses')) === (string) $plazo)>
                                    {{ $plazo }} meses
                                </option>
                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('plazo_meses')" class="mt-2" />

                    </div>

                    <div class="rounded-3xl border-t-4 border-green-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                        <label class="mb-3 block text-sm font-bold text-purple-700">
                            Ingreso mensual
                        </label>

                        <input
                            name="ingreso_mensual"
                            type="number"
                            min="1"
                            step="0.01"
                            value="{{ old('ingreso_mensual') }}"
                            class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            required
                        >

                        <x-input-error :messages="$errors->get('ingreso_mensual')" class="mt-2" />

                    </div>

                    <div class="rounded-3xl border-t-4 border-fuchsia-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                        <label class="mb-3 block text-sm font-bold text-purple-700">
                            Motivo del préstamo
                        </label>

                        <select
                            name="motivo"
                            class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            required
                        >

                            <option value="">Selecciona un motivo</option>

                            @foreach($motivos as $motivo)
                                <option value="{{ $motivo }}" @selected(old('motivo') === $motivo)>
                                    {{ $motivo }}
                                </option>
                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('motivo')" class="mt-2" />

                    </div>

                </div>

                {{-- INFORMACION LABORAL --}}
                <div class="border-y bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                    <div class="flex items-center gap-4">

                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-blue-600 to-cyan-600 text-3xl text-white shadow-xl shadow-blue-300 animate-float">
                            💼
                        </div>

                        <div>

                            <h2 class="text-3xl font-black text-gray-800">
                                Información laboral
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Estos datos ayudan a evaluar tu capacidad de pago.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="grid grid-cols-1 gap-6 p-8 md:grid-cols-2">

                    <div class="rounded-3xl border-t-4 border-cyan-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                        <label class="mb-3 block text-sm font-bold text-purple-700">
                            Tipo de empleo
                        </label>

                        <select
                            name="tipo_empleo"
                            class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            required
                        >

                            <option value="">Selecciona una opción</option>

                            @foreach($tiposEmpleo as $tipo)
                                <option value="{{ $tipo }}" @selected(old('tipo_empleo') === $tipo)>
                                    {{ $tipo }}
                                </option>
                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('tipo_empleo')" class="mt-2" />

                    </div>

                    <div class="rounded-3xl border-t-4 border-indigo-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                        <label class="mb-3 block text-sm font-bold text-purple-700">
                            Antigüedad laboral
                        </label>

                        <select
                            name="antiguedad_laboral"
                            class="w-full rounded-2xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            required
                        >

                            <option value="">Selecciona antigüedad</option>

                            @foreach($antiguedades as $antiguedad)
                                <option value="{{ $antiguedad }}" @selected(old('antiguedad_laboral') === $antiguedad)>
                                    {{ $antiguedad }}
                                </option>
                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('antiguedad_laboral')" class="mt-2" />

                    </div>

                </div>

                {{-- BOTONES --}}
                <div class="flex flex-col gap-3 border-t bg-gray-50 px-8 py-6 sm:flex-row sm:justify-end">

                    <a href="{{ route('cliente.dashboard') }}"
                       class="rounded-2xl border border-gray-300 bg-white px-6 py-3 text-center font-bold text-gray-600 transition hover:bg-gray-100 hover:scale-105">
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-8 py-3 font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105"
                    >
                        Enviar solicitud
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>