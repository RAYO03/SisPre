<x-app-layout>

    <div class="py-12">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-sm border p-6">

                <h1 class="text-2xl font-bold text-gray-800 mb-6">
                    Revisar Solicitud
                </h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-8">

                    <p>
                        <strong>Cliente:</strong>
                        {{ $solicitud->user->name }}
                    </p>

                    <p>
                        <strong>Email:</strong>
                        {{ $solicitud->user->email }}
                    </p>

                    <p>
                        <strong>Teléfono:</strong>
                        {{ $solicitud->telefono }}
                    </p>

                    <p>
                        <strong>CURP:</strong>
                        {{ $solicitud->curp }}
                    </p>

                    <p>
                        <strong>RFC:</strong>
                        {{ $solicitud->rfc }}
                    </p>

                    <p>
                        <strong>Ingreso mensual:</strong>
                        ${{ number_format($solicitud->ingreso_mensual, 2) }}
                    </p>

                    <p>
                        <strong>Monto solicitado:</strong>
                        ${{ number_format($solicitud->monto_solicitado, 2) }}
                    </p>

                    <p>
                        <strong>Plazo:</strong>
                        {{ $solicitud->plazo_meses }} meses
                    </p>

                    <p>
                        <strong>Frecuencia:</strong>
                        {{ ucfirst($solicitud->frecuencia_pago) }}
                    </p>

                    <p>
                        <strong>Estado:</strong>
                        {{ ucfirst($solicitud->estado) }}
                    </p>

                </div>

                @if($solicitud->estado === 'pendiente')

                    <div class="flex gap-4">

                        <form method="POST"
                              action="{{ route('admin.solicitudes.aprobar', $solicitud) }}">

                            @csrf

                            <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">

                                Aprobar Solicitud

                            </button>

                        </form>

                        <form method="POST"
                              action="{{ route('admin.solicitudes.rechazar', $solicitud) }}">

                            @csrf

                            <button type="submit"
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">

                                Rechazar Solicitud

                            </button>

                        </form>

                    </div>

                @else

                    <p class="text-gray-500">
                        Esta solicitud ya fue {{ $solicitud->estado }}.
                    </p>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>