<x-app-layout>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-sm border p-8">

                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    Solicitud de Préstamo
                </h1>

                <p class="text-gray-500 mb-8">
                    Completa la información requerida para solicitar tu préstamo.
                </p>

                <form method="POST"
                      action="{{ route('usuario.prestamos.store') }}"
                      enctype="multipart/form-data">

                    @csrf

                    {{-- DATOS PERSONALES --}}
                    <div class="mb-10">

                        <h2 class="text-xl font-semibold text-gray-800 mb-5">
                            Datos Personales
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <label class="text-sm text-gray-600">
                                    Nombre completo
                                </label>

                                <input type="text"
                                       name="nombre_completo"
                                       value="{{ auth()->user()->name }}"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Correo electrónico
                                </label>

                                <input type="email"
                                       name="email"
                                       value="{{ auth()->user()->email }}"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Teléfono
                                </label>

                                <input type="text"
                                       name="telefono"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    CURP
                                </label>

                                <input type="text"
                                       name="curp"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    RFC
                                </label>

                                <input type="text"
                                       name="rfc"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Fecha de nacimiento
                                </label>

                                <input type="date"
                                       name="fecha_nacimiento"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Ciudad
                                </label>

                                <input type="text"
                                       name="ciudad"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Estado
                                </label>

                                <input type="text"
                                       name="estado_residencia"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                        </div>

                        <div class="mt-5">
                            <label class="text-sm text-gray-600">
                                Dirección completa
                            </label>

                            <textarea name="direccion"
                                      rows="3"
                                      class="w-full mt-1 border rounded-xl px-4 py-3"></textarea>
                        </div>

                    </div>

                    {{-- INFORMACIÓN LABORAL --}}
                    <div class="mb-10">

                        <h2 class="text-xl font-semibold text-gray-800 mb-5">
                            Información Laboral
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <label class="text-sm text-gray-600">
                                    Empresa donde trabaja
                                </label>

                                <input type="text"
                                       name="empresa"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Puesto
                                </label>

                                <input type="text"
                                       name="puesto"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Antigüedad laboral
                                </label>

                                <input type="text"
                                       name="antiguedad_laboral"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Ingreso mensual
                                </label>

                                <input type="number"
                                       step="0.01"
                                       name="ingreso_mensual"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Tipo de empleo
                                </label>

                                <input type="text"
                                       name="tipo_empleo"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Teléfono de trabajo
                                </label>

                                <input type="text"
                                       name="telefono_trabajo"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                        </div>

                    </div>

                    {{-- INFORMACIÓN DEL PRÉSTAMO --}}
                    <div class="mb-10">

                        <h2 class="text-xl font-semibold text-gray-800 mb-5">
                            Información del Préstamo
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                            <div>
                                <label class="text-sm text-gray-600">
                                    Monto solicitado
                                </label>

                                <input type="number"
                                       step="0.01"
                                       name="monto_solicitado"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Plazo en meses
                                </label>

                                <input type="number"
                                       name="plazo_meses"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Frecuencia de pago
                                </label>

                                <select name="frecuencia_pago"
                                        class="w-full mt-1 border rounded-xl px-4 py-3">

                                    <option value="">Selecciona</option>

                                    <option value="semanal">
                                        Semanal
                                    </option>

                                    <option value="quincenal">
                                        Quincenal
                                    </option>

                                    <option value="mensual">
                                        Mensual
                                    </option>

                                </select>
                            </div>

                        </div>

                        <div class="mt-5">
                            <label class="text-sm text-gray-600">
                                Motivo del préstamo
                            </label>

                            <textarea name="motivo"
                                      rows="4"
                                      class="w-full mt-1 border rounded-xl px-4 py-3"></textarea>
                        </div>

                    </div>

                    {{-- REFERENCIAS --}}
                    <div class="mb-10">

                        <h2 class="text-xl font-semibold text-gray-800 mb-5">
                            Referencias Personales
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">

                            <input type="text"
                                   name="referencia1_nombre"
                                   placeholder="Referencia 1 - Nombre"
                                   class="border rounded-xl px-4 py-3">

                            <input type="text"
                                   name="referencia1_telefono"
                                   placeholder="Referencia 1 - Teléfono"
                                   class="border rounded-xl px-4 py-3">

                            <input type="text"
                                   name="referencia1_relacion"
                                   placeholder="Referencia 1 - Relación"
                                   class="border rounded-xl px-4 py-3">

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                            <input type="text"
                                   name="referencia2_nombre"
                                   placeholder="Referencia 2 - Nombre"
                                   class="border rounded-xl px-4 py-3">

                            <input type="text"
                                   name="referencia2_telefono"
                                   placeholder="Referencia 2 - Teléfono"
                                   class="border rounded-xl px-4 py-3">

                            <input type="text"
                                   name="referencia2_relacion"
                                   placeholder="Referencia 2 - Relación"
                                   class="border rounded-xl px-4 py-3">

                        </div>

                    </div>

                    {{-- DOCUMENTOS --}}
                    <div class="mb-10">

                        <h2 class="text-xl font-semibold text-gray-800 mb-5">
                            Documentos
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                            <div>
                                <label class="text-sm text-gray-600">
                                    INE
                                </label>

                                <input type="file"
                                       name="ine"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Comprobante de domicilio
                                </label>

                                <input type="file"
                                       name="comprobante_domicilio"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">
                                    Comprobante de ingresos
                                </label>

                                <input type="file"
                                       name="comprobante_ingresos"
                                       class="w-full mt-1 border rounded-xl px-4 py-3">
                            </div>

                        </div>

                    </div>

                    {{-- CONFIRMACIÓN --}}
                    <div class="mb-10 space-y-4">

                        <label class="flex items-center gap-3">
                            <input type="checkbox"
                                   name="acepta_terminos"
                                   required>

                            <span class="text-gray-700">
                                Acepto los términos y condiciones del préstamo.
                            </span>
                        </label>

                        <label class="flex items-center gap-3">
                            <input type="checkbox"
                                   name="autoriza_validacion"
                                   required>

                            <span class="text-gray-700">
                                Autorizo la validación de mi información.
                            </span>
                        </label>

                    </div>

                    <div class="flex justify-end">

                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium">

                            Enviar Solicitud

                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>