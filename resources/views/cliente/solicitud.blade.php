<x-app-layout>
    <div class="p-8">
        <div class="mx-auto max-w-5xl">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-purple-700">Solicitud de Prestamo</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Completa los datos para enviar tu solicitud a revision.
                    </p>
                </div>

                <a href="{{ route('cliente.solicitudes') }}"
                   class="rounded border border-purple-200 px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-50">
                    Mis solicitudes
                </a>
            </div>

            <form method="POST"
                  action="{{ route('cliente.solicitud.store') }}"
                  class="overflow-hidden rounded-2xl bg-white shadow">
                @csrf

                <div class="border-b px-6 py-5">
                    <h2 class="text-xl font-bold text-gray-800">Datos del prestamo</h2>
                    <p class="text-sm text-gray-500">El monto, plazo e ingreso ayudan a calcular tu pago mensual estimado.</p>
                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Monto solicitado</label>
                        <input name="monto_solicitado"
                               type="number"
                               min="{{ $montoMinimo }}"
                               max="{{ $montoMaximo }}"
                               step="0.01"
                               value="{{ old('monto_solicitado', request('monto_solicitado')) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               required>
                        <p class="mt-1 text-xs text-gray-500">
                            Rango permitido: ${{ number_format($montoMinimo, 2) }} a ${{ number_format($montoMaximo, 2) }}.
                        </p>
                        <x-input-error :messages="$errors->get('monto_solicitado')" class="mt-2" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Plazo en meses</label>
                        <select name="plazo_meses"
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                                required>
                            <option value="">Selecciona un plazo</option>
                            @foreach($plazos as $plazo)
                                <option value="{{ $plazo }}" @selected((string) old('plazo_meses', request('plazo_meses')) === (string) $plazo)>
                                    {{ $plazo }} meses
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('plazo_meses')" class="mt-2" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Ingreso mensual</label>
                        <input name="ingreso_mensual"
                               type="number"
                               min="1"
                               step="0.01"
                               value="{{ old('ingreso_mensual') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               required>
                        <x-input-error :messages="$errors->get('ingreso_mensual')" class="mt-2" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Motivo del prestamo</label>
                        <select name="motivo"
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500" required>
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

                <div class="border-y bg-gray-50 px-6 py-5">
                    <h2 class="text-xl font-bold text-gray-800">Informacion laboral</h2>
                    <p class="text-sm text-gray-500">Estos datos ayudan a evaluar tu capacidad de pago.</p>
                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Tipo de empleo</label>
                        <select name="tipo_empleo"
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                                required>
                            <option value="">Selecciona una opcion</option>
                            @foreach($tiposEmpleo as $tipo)
                                <option value="{{ $tipo }}" @selected(old('tipo_empleo') === $tipo)>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('tipo_empleo')" class="mt-2" />
                    </div>

                                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Antigüedad laboral
                    </label>

                    <select name="antiguedad_laboral"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                            required>
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

                <div class="flex justify-end gap-3 border-t px-6 py-5">
                    <a href="{{ route('cliente.dashboard') }}"
                       class="rounded border border-gray-200 px-5 py-3 text-gray-600 hover:bg-gray-50">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="rounded bg-purple-700 px-6 py-3 font-semibold text-white hover:bg-purple-800">
                        Enviar solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
