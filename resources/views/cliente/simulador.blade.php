<x-app-layout>
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-slide-up {
            animation: slideUp .8s ease-out forwards;
        }
    </style>

    <div class="min-h-screen bg-slate-100 px-4 py-10">
        <div class="mx-auto max-w-7xl animate-slide-up">

            <div class="mb-8">
                <span class="inline-flex rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                    Simulación financiera
                </span>

                <h1 class="mt-4 text-4xl font-black text-purple-700">
                    Simulador de Préstamo
                </h1>

                <p class="mt-2 text-gray-500">
                    Calcula una tabla estimada con sistema francés antes de enviar tu solicitud.
                </p>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-2xl">

                <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-fuchsia-50 px-6 py-5">
                    <h2 class="text-2xl font-black text-gray-800">
                        Calcula tu préstamo
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Ingresa los datos para visualizar cuotas, intereses y pagos aproximados.
                    </p>
                </div>

                <div class="p-6">
                    <livewire:simulador-prestamo />
                </div>

            </div>

        </div>
    </div>
</x-app-layout>