<x-app-layout>

<div class="p-8">

<h1 class="text-3xl font-bold text-purple-700 mb-6">
Préstamos
</h1>

<div class="bg-white rounded-2xl shadow overflow-hidden">

<table class="w-full">

<thead class="bg-purple-700 text-white">

<tr>
<th class="p-4">Cliente</th>
<th class="p-4">Monto</th>
<th class="p-4">Plazo</th>
<th class="p-4">Estado</th>
<th class="p-4">Acción</th>
</tr>

</thead>

<tbody>

@foreach($prestamos ?? [] as $prestamo)

<tr class="border-b">

<td class="p-4">
{{ $prestamo->user->name ?? 'N/A' }}
</td>

<td class="p-4">
${{ number_format($prestamo->capital,2) }}
</td>

<td class="p-4">
{{ $prestamo->plazo_meses }} meses
</td>

<td class="p-4">
{{ ucfirst($prestamo->estado) }}
</td>

<td class="p-4">

<a href="{{ route('admin.prestamos.show',$prestamo->id) }}"
class="bg-purple-700 text-white px-4 py-2 rounded">
Ver
</a>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

</x-app-layout>