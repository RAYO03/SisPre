<x-app-layout>

    <style>
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeUp .8s ease-out forwards;
        }
    </style>

    <div class="min-h-screen bg-slate-100 p-8">

        <div class="mx-auto max-w-7xl animate-fade-up">

            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div>

                    <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                        Administración de pagos
                    </span>

                    <h1 class="mt-4 whitespace-nowrap text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                        Pagos
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Historial completo de pagos registrados en el sistema.
                    </p>

                </div>

                <a href="{{ route('admin.pagos.create') }}"
                   class="rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-6 py-3 font-bold text-white shadow-xl shadow-purple-400 transition hover:scale-105">
                    + Registrar pago
                </a>

            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 p-6">

                    <h2 class="text-2xl font-black text-gray-800">
                        Historial de pagos
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Consulta todos los pagos realizados por los clientes.
                    </p>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[1200px] text-sm">

                        <thead class="bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 text-white shadow-lg">

                            <tr>
                                <th class="p-4 text-left">Folio</th>
                                <th class="p-4 text-left">Cliente</th>
                                <th class="p-4 text-left">Préstamo</th>
                                <th class="p-4 text-right">Monto</th>
                                <th class="p-4 text-center">Método</th>
                                <th class="p-4 text-center">Fecha</th>
                                <th class="p-4 text-center">Estado</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($pagos as $pago)

                                <tr class="border-b transition hover:bg-purple-50">

                                    <td class="p-4 whitespace-nowrap font-bold text-gray-800">
                                        {{ $pago->folio_pago }}
                                    </td>

                                    <td class="p-4 whitespace-nowrap">

                                        <div class="flex items-center gap-3">

                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-fuchsia-600 font-bold text-white shadow-md">
                                                {{ strtoupper(substr($pago->user->name ?? 'U',0,1)) }}
                                            </div>

                                            <span class="whitespace-nowrap font-semibold text-gray-800">
                                                {{ $pago->user->name ?? 'N/A' }}
                                            </span>

                                        </div>

                                    </td>

                                    <td class="p-4 whitespace-nowrap text-gray-700">
                                        {{ $pago->prestamo->folio ?? 'N/A' }}
                                    </td>

                                    <td class="p-4 text-right font-black text-green-700">
                                        ${{ number_format($pago->monto, 2) }}
                                    </td>

                                    <td class="p-4 text-center whitespace-nowrap">
                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700">
                                            {{ $pago->metodo_pago }}
                                        </span>
                                    </td>

                                    <td class="p-4 text-center whitespace-nowrap text-gray-700">
                                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                                    </td>

                                    <td class="p-4 text-center whitespace-nowrap">
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                            {{ $pago->estado_label }}
                                        </span>
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="p-10 text-center">

                                        <div class="mx-auto flex max-w-sm flex-col items-center">

                                            <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-3xl bg-purple-100 text-4xl">
                                                💳
                                            </div>

                                            <h3 class="text-xl font-black text-gray-800">
                                                No hay pagos registrados
                                            </h3>

                                            <p class="mt-2 text-sm text-gray-500">
                                                Los pagos aparecerán aquí cuando sean registrados.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                @if($pagos->hasPages())
                    <div class="border-t bg-gray-50 px-6 py-4">
                        {{ $pagos->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

</x-app-layout>