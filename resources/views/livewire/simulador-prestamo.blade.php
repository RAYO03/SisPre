<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Nuevo Préstamo</h2>

    <form method="POST" action="{{ route('prestamos.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-semibold mb-1">Cliente</label>
                <select name="cliente_id" wire:model.live="cliente_id" class="w-full border p-2 rounded">
                    <option value="">Selecciona un cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">
                            {{ $cliente->nombre }} {{ $cliente->apellido }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-1">Capital</label>
                <input type="number" step="0.01" name="capital" wire:model.live="capital"
                    class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block font-semibold mb-1">Tasa anual %</label>
                <input type="number" step="0.01" name="tasa_anual" wire:model.live="tasa_anual"
                    class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block font-semibold mb-1">Plazo en meses</label>
                <input type="number" name="plazo_meses" wire:model.live="plazo_meses"
                    class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block font-semibold mb-1">Fecha de inicio</label>
                <input type="date" name="fecha_inicio" wire:model.live="fecha_inicio"
                    class="w-full border p-2 rounded">
            </div>

            <input type="hidden" name="frecuencia" value="mensual">
        </div>

        <div class="bg-indigo-100 text-indigo-800 p-4 rounded mb-6">
            <p class="text-sm">Cuota mensual aproximada</p>
            <p class="text-3xl font-bold">${{ number_format($cuotaMensual, 2) }}</p>
        </div>

        <button class="bg-indigo-600 text-white px-4 py-2 rounded">
            Guardar Préstamo
        </button>

        <a href="{{ route('prestamos.index') }}" class="ml-2 text-gray-600">
            Cancelar
        </a>
    </form>

    <h3 class="text-xl font-bold mt-8 mb-3">Simulador de amortización</h3>

    <div class="overflow-x-auto">
        <table class="w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">#</th>
                    <th class="border p-2">Cuota</th>
                    <th class="border p-2">Interés</th>
                    <th class="border p-2">Capital</th>
                    <th class="border p-2">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tabla as $fila)
                    <tr>
                        <td class="border p-2">{{ $fila['numero'] }}</td>
                        <td class="border p-2">${{ number_format($fila['cuota'], 2) }}</td>
                        <td class="border p-2">${{ number_format($fila['interes'], 2) }}</td>
                        <td class="border p-2">${{ number_format($fila['capital'], 2) }}</td>
                        <td class="border p-2">${{ number_format($fila['saldo'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>