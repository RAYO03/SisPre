<div class="space-y-3 mb-4">
    <input name="nombre" placeholder="Nombre" class="w-full border p-2 rounded"
           value="{{ old('nombre', $cliente->nombre ?? '') }}">

    <input name="apellido" placeholder="Apellido" class="w-full border p-2 rounded"
           value="{{ old('apellido', $cliente->apellido ?? '') }}">

    <input name="email" placeholder="Email" class="w-full border p-2 rounded"
           value="{{ old('email', $cliente->email ?? '') }}">

    <input name="telefono" placeholder="Teléfono" class="w-full border p-2 rounded"
           value="{{ old('telefono', $cliente->telefono ?? '') }}">

    <input name="direccion" placeholder="Dirección" class="w-full border p-2 rounded"
           value="{{ old('direccion', $cliente->direccion ?? '') }}">

    <input name="ingresos_mensuales" type="number" step="0.01" placeholder="Ingresos mensuales"
           class="w-full border p-2 rounded"
           value="{{ old('ingresos_mensuales', $cliente->ingresos_mensuales ?? '') }}">

    <input name="referencia_nombre" placeholder="Nombre de referencia" class="w-full border p-2 rounded"
           value="{{ old('referencia_nombre', $cliente->referencia_nombre ?? '') }}">

    <input name="referencia_telefono" placeholder="Teléfono de referencia" class="w-full border p-2 rounded"
           value="{{ old('referencia_telefono', $cliente->referencia_telefono ?? '') }}">
</div>