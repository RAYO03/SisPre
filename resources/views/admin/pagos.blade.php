<x-app-layout>

<div class="p-8">

<h1 class="text-3xl font-bold text-purple-700 mb-6">
Pagos
</h1>

<div class="mb-5">

<a href="{{ route('admin.pagos.create') }}"
class="bg-green-600 text-white px-5 py-3 rounded-xl">

Registrar Pago

</a>

</div>

<div class="bg-white rounded-2xl shadow overflow-hidden">

<table class="w-full">

<thead class="bg-purple-700 text-white">

<tr>
<th class="p-4">Cliente</th>
<th class="p-4">Monto</th>
<th class="p-4">Fecha</th>
</tr>

</thead>

<tbody>

@foreach($pagos ?? [] as $pago)

<tr class="border-b">

<td class="p-4">
{{ $pago->user->name ?? 'N/A' }}
</td>

<td class="p-4">
${{ number_format($pago->monto,2) }}
</td>

<td class="p-4">
{{ $pago->fecha_pago }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

</x-app-layout>