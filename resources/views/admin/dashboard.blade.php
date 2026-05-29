<x-app-layout>
<div class="p-8">
    <h1 class="text-3xl font-bold text-purple-700 mb-6">Dashboard Administrador</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-gray-500">Clientes</p>
            <h2 class="text-3xl font-bold text-purple-700">{{ $totalClientes ?? 0 }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-gray-500">Solicitudes pendientes</p>
            <h2 class="text-3xl font-bold text-yellow-600">{{ $solicitudesPendientes ?? 0 }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-gray-500">Préstamos activos</p>
            <h2 class="text-3xl font-bold text-blue-600">{{ $prestamosActivos ?? 0 }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-gray-500">Pagos del mes</p>
            <h2 class="text-3xl font-bold text-green-600">${{ number_format($pagosMes ?? 0, 2) }}</h2>
        </div>
    </div>
</div>
</x-app-layout>