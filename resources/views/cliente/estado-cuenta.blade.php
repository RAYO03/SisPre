<x-app-layout>

<div class="p-8">

    <h1 class="text-3xl font-bold text-purple-700 mb-6">
        Estado de Cuenta
    </h1>

    <div class="grid grid-cols-3 gap-6 mb-6">

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500">Monto Original</p>
            <h2 class="text-2xl font-bold">
                $30,000
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500">Saldo Pendiente</p>
            <h2 class="text-2xl font-bold text-red-600">
                $14,500
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500">Próximo Pago</p>
            <h2 class="text-2xl font-bold text-green-600">
                $2,191
            </h2>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-purple-600 text-white">

                <tr>
                    <th class="p-4">Fecha</th>
                    <th class="p-4">Monto</th>
                    <th class="p-4">Estado</th>
                </tr>

            </thead>

            <tbody>

                <tr class="border-b">
                    <td class="p-4">15/06/2026</td>
                    <td class="p-4">$2,191</td>
                    <td class="p-4 text-green-600">Pagado</td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

</x-app-layout>