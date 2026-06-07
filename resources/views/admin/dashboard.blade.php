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
        <div class="mx-auto max-w-7xl animate-slide-up">

            {{-- ENCABEZADO --}}
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div>
                    <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                        Panel administrativo
                    </span>

                    <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                        Dashboard
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Resumen general de solicitudes, clientes, préstamos y pagos.
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('admin.dashboard') }}"
                      class="flex flex-col gap-3 rounded-3xl border border-purple-200 bg-white p-4 shadow-xl sm:flex-row sm:items-center">

                    <input type="month"
                           name="mes"
                           value="{{ request('mes', now()->format('Y-m')) }}"
                           class="rounded-xl border-gray-300 px-4 py-3 text-gray-700 shadow-sm focus:border-purple-500 focus:ring-purple-500">

                    <button class="rounded-xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-5 py-3 font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105">
                        Filtrar
                    </button>
                </form>

            </div>

            {{-- TARJETAS --}}
            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">

                <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="mb-4 text-4xl animate-float">
                        📋
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Solicitudes nuevas
                    </p>

                    <h2 class="mt-3 text-4xl font-black text-purple-700">
                        {{ $solicitudesPendientes }}
                    </h2>
                </div>

                <div class="rounded-3xl border-t-4 border-fuchsia-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="mb-4 text-4xl animate-float">
                        👥
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Clientes registrados
                    </p>

                    <h2 class="mt-3 text-4xl font-black text-fuchsia-700">
                        {{ $totalClientes }}
                    </h2>
                </div>

                <div class="rounded-3xl border-t-4 border-blue-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="mb-4 text-4xl animate-float">
                        💰
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Préstamos activos
                    </p>

                    <h2 class="mt-3 text-4xl font-black text-blue-600">
                        {{ $prestamosActivos }}
                    </h2>
                </div>

                <div class="rounded-3xl border-t-4 border-green-600 bg-white p-6 shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="mb-4 text-4xl animate-float">
                        💵
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Pagos recibidos
                    </p>

                    <h2 class="mt-3 text-4xl font-black text-green-600">
                        ${{ number_format($pagosMes, 2) }}
                    </h2>
                </div>

            </div>

            {{-- GRAFICAS --}}
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

                <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">
                    <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-6 py-5">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-600 to-fuchsia-600 text-2xl text-white shadow-lg shadow-purple-300 animate-float">
                                📈
                            </div>

                            <div>
                                <h2 class="text-2xl font-black text-gray-800">
                                    Préstamos por semana
                                </h2>

                                <p class="mt-1 text-sm text-gray-500">
                                    Comportamiento semanal del mes seleccionado.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if($prestamosSemana->count() > 0)
                            <div class="h-96">
                                <canvas id="prestamosSemanaChart"></canvas>
                            </div>
                        @else
                            <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-10 text-center shadow-xl">
                                <div class="mb-4 text-5xl animate-float">
                                    📊
                                </div>

                                <p class="font-semibold text-gray-500">
                                    No hay préstamos en este rango.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">
                    <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-6 py-5">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-600 to-fuchsia-600 text-2xl text-white shadow-lg shadow-purple-300 animate-float">
                                🍩
                            </div>

                            <div>
                                <h2 class="text-2xl font-black text-gray-800">
                                    Préstamos por estado
                                </h2>

                                <p class="mt-1 text-sm text-gray-500">
                                    Distribución actual de préstamos por estado.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if($prestamosPorEstado->sum() > 0)
                            <div class="mx-auto h-96 w-96 max-w-full">
                                <canvas id="prestamosEstadoChart"></canvas>
                            </div>
                        @else
                            <div class="rounded-3xl border-t-4 border-fuchsia-600 bg-white p-10 text-center shadow-xl">
                                <div class="mb-4 text-5xl animate-float">
                                    📉
                                </div>

                                <p class="font-semibold text-gray-500">
                                    No hay datos para mostrar.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const semanas = @json($prestamosSemana->keys());
        const cantidadesSemana = @json($prestamosSemana->values());

        if (document.getElementById('prestamosSemanaChart')) {
            new Chart(document.getElementById('prestamosSemanaChart'), {
                type: 'line',
                data: {
                    labels: semanas.map(semana => 'Semana ' + semana),
                    datasets: [{
                        label: 'Préstamos',
                        data: cantidadesSemana,
                        borderColor: '#7c3aed',
                        backgroundColor: 'rgba(124, 58, 237, 0.15)',
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        borderWidth: 3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1800,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        const estados = @json($prestamosPorEstadoLabels);
        const cantidadesEstado = @json($prestamosPorEstado->values());

        if (document.getElementById('prestamosEstadoChart')) {
            new Chart(document.getElementById('prestamosEstadoChart'), {
                type: 'doughnut',
                data: {
                    labels: estados,
                    datasets: [{
                        data: cantidadesEstado,
                        backgroundColor: [
                            '#7c3aed',
                            '#c026d3',
                            '#2563eb',
                            '#16a34a',
                            '#f97316',
                            '#dc2626'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1800,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    </script>

</x-app-layout>