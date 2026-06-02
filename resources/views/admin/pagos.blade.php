<x-app-layout>
    <div class="space-y-6 p-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-purple-700">Pagos</h1>
                <p class="mt-1 text-sm text-gray-500">Historial de pagos registrados.</p>
            </div>

            <a href="{{ route('admin.pagos.create') }}"
               class="rounded bg-purple-700 px-5 py-3 font-semibold text-white hover:bg-purple-800">
                Registrar pago
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl bg-white shadow">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-purple-700 text-white">
                        <tr>
                            <th class="p-4 text-left">Folio</th>
                            <th class="p-4 text-left">Cliente</th>
                            <th class="p-4 text-left">Prestamo</th>
                            <th class="p-4 text-right">Monto</th>
                            <th class="p-4 text-center">Metodo</th>
                            <th class="p-4 text-center">Fecha</th>
                            <th class="p-4 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagos as $pago)
                            <tr class="border-b last:border-b-0 hover:bg-gray-50">
                                <td class="p-4 font-medium text-gray-800">
                                    {{ $pago->folio_pago }}
                                </td>
                                <td class="p-4">
                                    {{ $pago->user->name ?? 'N/A' }}
                                </td>
                                <td class="p-4">
                                    {{ $pago->prestamo->folio ?? 'N/A' }}
                                </td>
                                <td class="p-4 text-right font-semibold text-green-700">
                                    ${{ number_format($pago->monto, 2) }}
                                </td>
                                <td class="p-4 text-center">
                                    {{ $pago->metodo_pago }}
                                </td>
                                <td class="p-4 text-center">
                                    {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                                </td>
                                <td class="p-4 text-center">
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        {{ $pago->estado_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-gray-500">
                                    No hay pagos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pagos->hasPages())
                <div class="border-t px-6 py-4">
                    {{ $pagos->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
