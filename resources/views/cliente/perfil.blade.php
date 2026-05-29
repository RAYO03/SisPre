<x-app-layout>
<div class="p-8">
    <h1 class="text-3xl font-bold text-purple-700 mb-6">Mi Perfil</h1>

    <div class="bg-white rounded-2xl shadow p-8 max-w-3xl">
        <div class="space-y-5">
            <div>
                <p class="text-gray-500">Nombre</p>
                <h2 class="text-xl font-semibold">{{ auth()->user()->name }}</h2>
            </div>

            <div>
                <p class="text-gray-500">Correo</p>
                <h2 class="text-xl font-semibold">{{ auth()->user()->email }}</h2>
            </div>

            <div>
                <p class="text-gray-500">Tipo de usuario</p>
                <h2 class="text-xl font-semibold">{{ auth()->user()->tipo_usuario }}</h2>
            </div>
        </div>
    </div>
</div>
</x-app-layout>