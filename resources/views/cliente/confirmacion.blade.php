<x-app-layout>
<div class="p-8">
    <div class="bg-white rounded-2xl shadow p-10 text-center max-w-2xl mx-auto">
        <div class="text-6xl mb-4">✅</div>

        <h1 class="text-3xl font-bold text-gray-800">¡Solicitud enviada!</h1>

        <p class="text-gray-500 mt-3">
            Hemos recibido tu solicitud correctamente.
        </p>

        <div class="bg-green-50 text-green-700 rounded-xl p-5 mt-6">
            Folio de solicitud:
            <strong>{{ $solicitud->folio ?? 'CR-2026-000123' }}</strong>
        </div>

        <a href="{{ route('cliente.solicitudes') }}"
           class="inline-block mt-6 bg-purple-700 text-white px-6 py-3 rounded-xl">
            Ir a mis solicitudes
        </a>
    </div>
</div>
</x-app-layout>