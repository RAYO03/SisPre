<x-app-layout>

<div class="p-8">

<h1 class="text-3xl font-bold text-purple-700 mb-6">
Detalle del Préstamo
</h1>

<div class="bg-white rounded-2xl shadow p-8">

<div class="grid grid-cols-2 gap-6">

<div>
<label class="font-bold">Cliente</label>
<p>{{ $prestamo->user->name ?? '' }}</p>
</div>

<div>
<label class="font-bold">Monto</label>
<p>${{ number_format($prestamo->capital,2) }}</p>
</div>

<div>
<label class="font-bold">Plazo</label>
<p>{{ $prestamo->plazo_meses }} meses</p>
</div>

<div>
<label class="font-bold">Estado</label>
<p>{{ ucfirst($prestamo->estado) }}</p>
</div>

</div>

</div>

</div>

</x-app-layout>