<x-app-layout>
<div class="p-8">
    <h1 class="text-3xl font-bold text-purple-700 mb-6">Simulador de Préstamo</h1>

    <div class="bg-white rounded-2xl shadow p-8 max-w-3xl">
        <form>
            <label class="block font-semibold mb-2">Monto solicitado</label>
            <input type="number" value="30000" class="w-full rounded-lg border-gray-300 mb-5">

            <label class="block font-semibold mb-2">Plazo</label>
            <select class="w-full rounded-lg border-gray-300 mb-5">
                <option>6 meses</option>
                <option selected>12 meses</option>
                <option>18 meses</option>
                <option>24 meses</option>
            </select>

            <div class="bg-purple-50 rounded-xl p-6 mb-6">
                <p class="text-gray-500">Pago mensual aproximado</p>
                <h2 class="text-4xl font-bold text-purple-700">$2,191 MXN</h2>
                <p class="text-sm text-gray-500 mt-2">Tasa estimada: 19.9% anual</p>
            </div>

            <a href="{{ route('cliente.solicitud') }}"
               class="inline-block bg-purple-700 text-white px-6 py-3 rounded-xl font-semibold">
                Continuar
            </a>
        </form>
    </div>
</div>
</x-app-layout>