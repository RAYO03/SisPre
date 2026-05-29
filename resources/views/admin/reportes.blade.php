<x-app-layout>

<div class="p-8">

<h1 class="text-3xl font-bold text-purple-700 mb-6">
Reportes
</h1>

<div class="grid grid-cols-4 gap-6">

<div class="bg-white shadow rounded-2xl p-6">
<h2 class="text-gray-500">Clientes</h2>
<p class="text-3xl font-bold text-purple-700">
{{ $totalClientes ?? 0 }}
</p>
</div>

<div class="bg-white shadow rounded-2xl p-6">
<h2 class="text-gray-500">Préstamos</h2>
<p class="text-3xl font-bold text-blue-600">
{{ $totalPrestamos ?? 0 }}
</p>
</div>

<div class="bg-white shadow rounded-2xl p-6">
<h2 class="text-gray-500">Pagos</h2>
<p class="text-3xl font-bold text-green-600">
{{ $totalPagos ?? 0 }}
</p>
</div>

<div class="bg-white shadow rounded-2xl p-6">
<h2 class="text-gray-500">Monto Prestado</h2>
<p class="text-3xl font-bold text-yellow-600">
${{ number_format($montoPrestado ?? 0,2) }}
</p>
</div>

</div>

</div>

</x-app-layout>