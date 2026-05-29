<x-app-layout>

<div class="p-8">

<h1 class="text-3xl font-bold text-purple-700 mb-6">
Registrar Pago
</h1>

<form method="POST"
action="{{ route('admin.pagos.store') }}"
class="bg-white rounded-2xl shadow p-8">

@csrf

<div class="mb-4">

<label>Préstamo</label>

<select name="prestamo_id"
class="w-full rounded-lg">

@foreach($prestamos ?? [] as $prestamo)

<option value="{{ $prestamo->id }}">
{{ $prestamo->id }}
</option>

@endforeach

</select>

</div>

<div class="mb-4">

<label>Monto</label>

<input type="number"
name="monto"
class="w-full rounded-lg">

</div>

<button class="bg-purple-700 text-white px-6 py-3 rounded-xl">
Guardar Pago
</button>

</form>

</div>

</x-app-layout>