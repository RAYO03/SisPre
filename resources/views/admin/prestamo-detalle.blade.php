<x-app-layout>
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-slide-up {
            animation: slideUp .8s ease-out forwards;
        }
    </style>

    <div class="min-h-screen bg-slate-100 px-4 py-10">

        <div class="mx-auto max-w-6xl animate-slide-up">

            {{-- ENCABEZADO --}}
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div>
                    <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                        Información del préstamo
                    </span>

                    <h1 class="mt-4 text-4xl font-black text-purple-700">
                        Detalle del Préstamo
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Consulta toda la información financiera y administrativa del préstamo.
                    </p>
                </div>

                <a href="{{ route('admin.prestamos') }}"
                   class="rounded-2xl border border-purple-200 bg-white px-5 py-3 text-sm font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-50">
                    ← Volver
                </a>

            </div>

            {{-- TARJETAS RESUMEN --}}
            <div class="mb-8 grid grid-cols-1 gap-5 md:grid-cols-3">

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Monto original</p>

                    <h2 class="mt-2 text-2xl font-black text-gray-900">
                        ${{ number_format($prestamo->monto_original ?? $prestamo->solicitud?->monto_solicitado ?? $prestamo->monto_total, 2) }}
                    </h2>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Monto total</p>

                    <h2 class="mt-2 text-2xl font-black text-blue-600">
                        ${{ number_format($prestamo->monto_total, 2) }}
                    </h2>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-sm text-gray-500">Saldo pendiente</p>

                    <h2 class="mt-2 text-2xl font-black text-green-600">
                        ${{ number_format($prestamo->saldo_pendiente, 2) }}
                    </h2>
                </div>

            </div>

            {{-- INFORMACIÓN --}}
            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Información general
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Datos completos del préstamo seleccionado.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2 lg:grid-cols-3">

                    <div class="rounded-2xl border border-gray-200 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                            Cliente
                        </p>

                        <p class="mt-2 text-lg font-bold text-gray-800">
                            {{ $prestamo->user->name ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-gray-200 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                            Folio
                        </p>

                        <p class="mt-2 text-lg font-bold text-gray-800">
                            {{ $prestamo->folio }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-gray-200 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                            Tasa anual
                        </p>

                        <p class="mt-2 text-lg font-bold text-gray-800">
                            {{ number_format($prestamo->tasa_interes, 2) }}%
                        </p>
                    </div>

                    <div class="rounded-2xl border border-gray-200 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                            Pago mensual
                        </p>

                        <p class="mt-2 text-lg font-bold text-gray-800">
                            ${{ number_format($prestamo->pago_mensual, 2) }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-gray-200 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                            Plazo
                        </p>

                        <p class="mt-2 text-lg font-bold text-gray-800">
                            {{ $prestamo->plazo_meses }} meses
                        </p>
                    </div>

                    <div class="rounded-2xl border border-gray-200 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                            Fecha inicio
                        </p>

                        <p class="mt-2 text-lg font-bold text-gray-800">
                            {{ $prestamo->fecha_inicio }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-gray-200 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                            Fecha final
                        </p>

                        <p class="mt-2 text-lg font-bold text-gray-800">
                            {{ $prestamo->fecha_final }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-gray-200 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">
                            Estado
                        </p>

                        <div class="mt-2">
                            @if($prestamo->estado === \App\Support\Estado::ACTIVO)
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                    {{ $prestamo->estado_label }}
                                </span>

                            @elseif($prestamo->estado === \App\Support\Estado::EN_MORA)
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                    {{ $prestamo->estado_label }}
                                </span>

                            @elseif($prestamo->estado === \App\Support\Estado::LIQUIDADO)
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700">
                                    {{ $prestamo->estado_label }}
                                </span>

                            @else
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-700">
                                    {{ $prestamo->estado_label }}
                                </span>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</x-app-layout>