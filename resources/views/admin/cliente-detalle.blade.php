<x-app-layout>

<div class="p-8">

    <h1 class="text-3xl font-bold text-purple-700 mb-6">
        Detalle del Cliente
    </h1>

    <div class="bg-white rounded-2xl shadow p-8">

        <div class="grid grid-cols-2 gap-6">

            <div>
                <label class="font-bold">Nombre</label>
                <p>{{ $cliente->user->name ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="font-bold">Correo</label>
                <p>{{ $cliente->user->email ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="font-bold">Teléfono</label>
                <p>{{ $cliente->telefono ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="font-bold">CURP</label>
                <p>{{ $cliente->curp ?? 'N/A' }}</p>
            </div>

        </div>

    </div>

</div>

</x-app-layout>