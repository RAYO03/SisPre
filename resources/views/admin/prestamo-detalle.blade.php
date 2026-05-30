<x-app-layout>
    <div class="p-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-purple-700">
                Detalle del Prestamo
            </h1>

            <a href="{{ route('admin.prestamos') }}"
               class="rounded border border-purple-200 px-4 py-2 text-purple-700 hover:bg-purple-50">
                Volver
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="font-bold text-gray-700">Cliente</label>
                    <p>{{ $prestamo->user->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="font-bold text-gray-700">Folio</label>
                    <p>{{ $prestamo->folio }}</p>
                </div>

                <div>
                    <label class="font-bold text-gray-700">Monto total</label>
                    <p>${{ number_format($prestamo->monto_total, 2) }}</p>
                </div>

                <div>
                    <label class="font-bold text-gray-700">Saldo pendiente</label>
                    <p class="font-semibold text-green-700">${{ number_format($prestamo->saldo_pendiente, 2) }}</p>
                </div>

                <div>
                    <label class="font-bold text-gray-700">Pago mensual</label>
                    <p>${{ number_format($prestamo->pago_mensual, 2) }}</p>
                </div>

                <div>
                    <label class="font-bold text-gray-700">Plazo</label>
                    <p>{{ $prestamo->plazo_meses }} meses</p>
                </div>

                <div>
                    <label class="font-bold text-gray-700">Fecha inicio</label>
                    <p>{{ $prestamo->fecha_inicio }}</p>
                </div>

                <div>
                    <label class="font-bold text-gray-700">Fecha final</label>
                    <p>{{ $prestamo->fecha_final }}</p>
                </div>

                <div>
                    <label class="font-bold text-gray-700">Estado</label>
                    <p>
                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                            {{ ucfirst($prestamo->estado) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
