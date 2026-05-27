@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Pagos</h1>
    

</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Cliente</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Préstamo #</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Fecha</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Monto</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pagos as $pago)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">
                    {{ $pago->prestamo->cliente->nombre_completo ?? 'N/A' }}
                </td>
                <td class="px-4 py-3 text-gray-600">#{{ $pago->prestamo->id }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                <td class="px-4 py-3 text-right font-semibold text-gray-800">
                    ${{ number_format($pago->monto, 2) }}
                </td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('pagos.show', $pago) }}" class="text-indigo-600 hover:underline">Ver</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-10 text-center text-gray-400">
                    No hay pagos registrados aún.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $pagos->links() }}
</div>
@endsection