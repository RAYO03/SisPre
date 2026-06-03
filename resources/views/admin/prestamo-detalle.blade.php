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

        <div class="mx-auto max-w-7xl animate-slide-up">

            {{-- ENCABEZADO --}}
            <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                        Información del préstamo
                    </span>

                    <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                        Detalle del Préstamo
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Consulta toda la información financiera y administrativa del préstamo.
                    </p>

                </div>

                <div class="flex flex-wrap gap-3">

                    <a href="{{ route('admin.prestamos') }}"
                       class="rounded-2xl border border-purple-300 bg-white px-5 py-3 text-sm font-bold text-purple-700 shadow-sm transition hover:scale-105 hover:bg-purple-100">
                        ← Volver
                    </a>

                    <a href="{{ route('admin.prestamos.edit', $prestamo->id) }}"
                       class="rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-600 px-5 py-3 text-sm font-bold text-white shadow-lg transition hover:scale-105">
                        ✏️ Editar
                    </a>

                    <form action="{{ route('admin.prestamos.destroy', $prestamo->id) }}"
                          method="POST"
                          onsubmit="return confirm('¿Seguro que deseas eliminar este préstamo? También se eliminarán sus pagos y cuotas.')">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="rounded-2xl bg-gradient-to-r from-red-600 to-rose-600 px-5 py-3 text-sm font-bold text-white shadow-lg transition hover:scale-105">
                            🗑️ Eliminar
                        </button>

                    </form>

                </div>

            </div>

            {{-- RESUMEN --}}
            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">

                <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                    <div class="mb-4 text-3xl">💰</div>

                    <p class="text-sm font-semibold text-gray-500">
                        Monto original
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-purple-700">
                        ${{ number_format($prestamo->monto_original ?? $prestamo->solicitud?->monto_solicitado ?? $prestamo->monto_total, 2) }}
                    </h2>

                </div>

                <div class="rounded-3xl border-t-4 border-blue-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                    <div class="mb-4 text-3xl">📊</div>

                    <p class="text-sm font-semibold text-gray-500">
                        Monto total
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-blue-700">
                        ${{ number_format($prestamo->monto_total, 2) }}
                    </h2>

                </div>

                <div class="rounded-3xl border-t-4 border-green-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">

                    <div class="mb-4 text-3xl">💵</div>

                    <p class="text-sm font-semibold text-gray-500">
                        Saldo pendiente
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-green-700">
                        ${{ number_format($prestamo->saldo_pendiente, 2) }}
                    </h2>

                </div>

            </div>

            {{-- INFORMACIÓN --}}
            <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-6 py-5">

                    <h2 class="text-2xl font-black text-gray-800">
                        Información general
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Datos completos del préstamo seleccionado.
                    </p>

                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2 xl:grid-cols-3">

                    <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-wide text-purple-700">
                            Cliente
                        </p>

                        <p class="mt-2 text-lg font-black text-gray-800">
                            {{ $prestamo->user->name ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-wide text-purple-700">
                            Folio
                        </p>

                        <p class="mt-2 text-lg font-black text-gray-800">
                            {{ $prestamo->folio }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-wide text-purple-700">
                            Tasa anual
                        </p>

                        <p class="mt-2 text-lg font-black text-gray-800">
                            {{ number_format($prestamo->tasa_interes, 2) }}%
                        </p>
                    </div>

                    <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-wide text-purple-700">
                            Pago mensual
                        </p>

                        <p class="mt-2 text-lg font-black text-gray-800">
                            ${{ number_format($prestamo->pago_mensual, 2) }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-wide text-purple-700">
                            Plazo
                        </p>

                        <p class="mt-2 text-lg font-black text-gray-800">
                            {{ $prestamo->plazo_meses }} meses
                        </p>
                    </div>

                    <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-wide text-purple-700">
                            Fecha inicio
                        </p>

                        <p class="mt-2 text-lg font-black text-gray-800">
                            {{ $prestamo->fecha_inicio }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-wide text-purple-700">
                            Fecha final
                        </p>

                        <p class="mt-2 text-lg font-black text-gray-800">
                            {{ $prestamo->fecha_final }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-wide text-purple-700">
                            Estado
                        </p>

                        <div class="mt-3">

                            @if($prestamo->estado === \App\Support\Estado::ACTIVO)

                                <span class="rounded-full bg-green-100 px-4 py-2 text-sm font-bold text-green-700">
                                    {{ $prestamo->estado_label }}
                                </span>

                            @elseif($prestamo->estado === \App\Support\Estado::EN_MORA)

                                <span class="rounded-full bg-red-100 px-4 py-2 text-sm font-bold text-red-700">
                                    {{ $prestamo->estado_label }}
                                </span>

                            @elseif($prestamo->estado === \App\Support\Estado::LIQUIDADO)

                                <span class="rounded-full bg-blue-100 px-4 py-2 text-sm font-bold text-blue-700">
                                    {{ $prestamo->estado_label }}
                                </span>

                            @else

                                <span class="rounded-full bg-gray-100 px-4 py-2 text-sm font-bold text-gray-700">
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