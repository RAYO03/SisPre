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

        <div class="mx-auto max-w-7xl animate-slide-up">

            {{-- ENCABEZADO --}}
            <div class="mb-8">

                <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                    Reportes y estadísticas
                </span>

                <h1 class="mt-4 text-4xl font-black text-purple-700">
                    Reportes
                </h1>

                <p class="mt-2 text-gray-500">
                    Visualiza métricas generales del sistema Credify.
                </p>

            </div>

            {{-- TARJETAS --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">

                {{-- CLIENTES --}}
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-xl transition hover:-translate-y-1 hover:shadow-2xl">

                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-100 text-2xl">
                        👥
                    </div>

                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                        Clientes
                    </h2>

                    <p class="mt-3 text-4xl font-black text-purple-700">
                        {{ $totalClientes ?? 0 }}
                    </p>

                    <p class="mt-2 text-sm text-gray-400">
                        Clientes registrados en el sistema.
                    </p>

                </div>

                {{-- PRESTAMOS --}}
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-xl transition hover:-translate-y-1 hover:shadow-2xl">

                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-2xl">
                        💰
                    </div>

                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                        Préstamos
                    </h2>

                    <p class="mt-3 text-4xl font-black text-blue-600">
                        {{ $totalPrestamos ?? 0 }}
                    </p>

                    <p class="mt-2 text-sm text-gray-400">
                        Préstamos creados y administrados.
                    </p>

                </div>

                {{-- PAGOS --}}
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-xl transition hover:-translate-y-1 hover:shadow-2xl">

                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-2xl">
                        💳
                    </div>

                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                        Pagos
                    </h2>

                    <p class="mt-3 text-4xl font-black text-green-600">
                        {{ $totalPagos ?? 0 }}
                    </p>

                    <p class="mt-2 text-sm text-gray-400">
                        Pagos registrados por clientes.
                    </p>

                </div>

                {{-- MONTO PRESTADO --}}
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-xl transition hover:-translate-y-1 hover:shadow-2xl">

                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-100 text-2xl">
                        📈
                    </div>

                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                        Monto Prestado
                    </h2>

                    <p class="mt-3 text-3xl font-black text-yellow-600">
                        ${{ number_format($montoPrestado ?? 0, 2) }}
                    </p>

                    <p class="mt-2 text-sm text-gray-400">
                        Capital total otorgado en préstamos.
                    </p>

                </div>

            </div>

            {{-- PANEL EXTRA --}}
            <div class="mt-8 rounded-3xl border border-gray-200 bg-white p-8 shadow-2xl">

                <h2 class="text-2xl font-black text-gray-800">
                    Resumen General
                </h2>

                <p class="mt-2 text-gray-500">
                    Este panel muestra una visión rápida del comportamiento general de la plataforma Credify.
                </p>

                <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-4">

                    <div class="rounded-2xl bg-purple-50 p-4 text-center">
                        <p class="text-sm text-gray-500">Clientes</p>
                        <p class="text-2xl font-black text-purple-700">
                            {{ $totalClientes ?? 0 }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-blue-50 p-4 text-center">
                        <p class="text-sm text-gray-500">Préstamos</p>
                        <p class="text-2xl font-black text-blue-600">
                            {{ $totalPrestamos ?? 0 }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-green-50 p-4 text-center">
                        <p class="text-sm text-gray-500">Pagos</p>
                        <p class="text-2xl font-black text-green-600">
                            {{ $totalPagos ?? 0 }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-yellow-50 p-4 text-center">
                        <p class="text-sm text-gray-500">Capital</p>
                        <p class="text-2xl font-black text-yellow-600">
                            ${{ number_format($montoPrestado ?? 0, 2) }}
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>
</x-app-layout>