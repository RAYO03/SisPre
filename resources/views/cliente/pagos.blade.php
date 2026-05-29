<x-app-layout>
    <div class="p-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-purple-700">Mis Pagos</h1>

            @if(($prestamosActivos ?? collect())->count() === 1)
                <a href="{{ route('cliente.pagos.create', $prestamosActivos->first()->id) }}"
                   class="rounded bg-purple-700 px-5 py-3 font-semibold text-white hover:bg-purple-800">
                    Registrar pago
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(($prestamosActivos ?? collect())->count() > 1)
            <div class="mb-6 rounded-2xl bg-white p-5 shadow">
                <p class="mb-3 font-semibold text-gray-700">Registrar pago por prestamo</p>
                <div class="flex flex-wrap gap-3">
                    @foreach($prestamosActivos as $prestamo)
                        <a href="{{ route('cliente.pagos.create', $prestamo->id) }}"
                           class="rounded bg-purple-700 px-4 py-2 text-white hover:bg-purple-800">
                            {{ $prestamo->folio }} - ${{ number_format($prestamo->saldo_pendiente, 2) }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-purple-700 text-white">
                    <tr>
                        <th class="p-4 text-left">Folio</th>
                        <th class="p-4 text-left">Monto</th>
                        <th class="p-4 text-left">Metodo</th>
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
                            <td class="p-4">
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    {{ ucfirst($pago->estado) }}
                                </span>
                            </td>
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
