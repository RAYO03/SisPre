<x-app-layout>
<div class="p-8">
    <h1 class="text-3xl font-bold text-purple-700 mb-6">Solicitud de Préstamo</h1>

    <form method="POST" action="{{ route('cliente.solicitud.store') }}" class="bg-white rounded-2xl shadow p-8 grid grid-cols-1 md:grid-cols-2 gap-5">
        @csrf

        <input name="monto_solicitado" type="number" placeholder="Monto solicitado" class="rounded-lg border-gray-300" required>
        <input name="plazo_meses" type="number" placeholder="Plazo en meses" class="rounded-lg border-gray-300" required>
        <input name="ingreso_mensual" type="number" placeholder="Ingreso mensual" class="rounded-lg border-gray-300" required>

        <select name="tipo_empleo" class="rounded-lg border-gray-300" required>
            <option value="">Tipo de empleo</option>
            <option value="Empleado">Empleado</option>
            <option value="Independiente">Independiente</option>
            <option value="Negocio propio">Negocio propio</option>
        </select>

        <input name="antiguedad_laboral" placeholder="Antigüedad laboral" class="rounded-lg border-gray-300" required>

        <select name="motivo" class="rounded-lg border-gray-300">
            <option value="">Motivo del préstamo</option>
            <option value="Emergencia">Emergencia</option>
            <option value="Negocio">Negocio</option>
            <option value="Personal">Personal</option>
        </select>

        <div class="md:col-span-2">
            <button class="w-full bg-purple-700 text-white py-3 rounded-xl font-semibold">
                Enviar solicitud
            </button>
        </div>
    </form>
</div>
</x-app-layout>