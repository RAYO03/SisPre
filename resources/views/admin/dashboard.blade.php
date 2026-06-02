<x-app-layout>
<div class="p-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-purple-700">Dashboard</h1>
            <p class="text-gray-500">Resumen general</p>
        </div>

        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex gap-2">
            <input 
                type="month" 
                name="mes" 
                value="{{ request('mes', now()->format('Y-m')) }}"
                class="rounded-lg border-gray-300"
            >

            <button class="bg-purple-700 hover:bg-purple-800 text-white px-4 rounded-lg">
                Filtrar
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-gray-500">Solicitudes nuevas</p>
            <h2 class="text-3xl font-bold text-purple-700">{{ $solicitudesPendientes }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-gray-500">Clientes registrados</p>
            <h2 class="text-3xl font-bold text-purple-700">{{ $totalClientes }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-gray-500">Préstamos activos</p>
            <h2 class="text-3xl font-bold text-blue-600">{{ $prestamosActivos }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-gray-500">Pagos recibidos</p>
            <h2 class="text-3xl font-bold text-green-600">
                ${{ number_format($pagosMes, 2) }}
            </h2>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl">

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-xl font-bold mb-6">
                Préstamos por semana
            </h2>

            @if($prestamosSemana->count() > 0)
                <div class="h-64">
                    <canvas id="prestamosSemanaChart"></canvas>
                </div>
            @else
                <p class="text-gray-500">No hay préstamos en este rango.</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-xl font-bold mb-6">
                Préstamos por estado
            </h2>

            @if($prestamosPorEstado->sum() > 0)
                <div class="w-64 h-64 mx-auto">
                    <canvas id="prestamosEstadoChart"></canvas>
                </div>
            @else
                <p class="text-gray-500">No hay datos para mostrar.</p>
            @endif
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
                    backgroundColor: '#7c3aed',
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    borderWidth: 3,
                    fill: false
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
                        position: 'right'
                    }
                }
            }
        });
    }
</script>

</x-app-layout>
