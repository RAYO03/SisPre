<x-app-layout>
<div class="p-8">
    <h1 class="text-3xl font-bold text-purple-700 mb-6">Mis Pagos</h1>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-purple-700 text-white">
                <tr>
                    <th class="p-4 text-left">Folio</th>
                    <th class="p-4 text-left">Monto</th>
                    <th class="p-4 text-left">Método</th>
                    <th class="p-4 text-left">Fecha</th>
                    <th class="p-4 text-left">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pagos ?? [] as $pago)
                    <tr class="border-b">
                        <td class="p-4">{{ $pago->folio_pago }}</td>
                        <td class="p-4">${{ number_format($pago->monto, 2) }}</td>
                        <td class="p-4">{{ $pago->metodo_pago }}</td>
                        <td class="p-4">{{ $pago->fecha_pago }}</td>
                        <td class="p-4">{{ ucfirst($pago->estado) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">
                            No tienes pagos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>