<x-app-layout>

    <style>
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%,100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-8px);
            }
        }

        .animate-slide-up {
            animation: slideUp .8s ease-out forwards;
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
    </style>

    <div class="min-h-screen bg-slate-100 px-4 py-10">

        <div class="mx-auto max-w-7xl animate-slide-up">

            {{-- ENCABEZADO --}}
            <div class="mb-8">

                <span class="inline-flex rounded-full border border-purple-300 bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">
                    Simulación financiera
                </span>

                <h1 class="mt-4 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                    Simulador de Préstamo
                </h1>

                <p class="mt-2 text-gray-500">
                    Calcula una tabla estimada con sistema francés antes de enviar tu solicitud.
                </p>

            </div>

            <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                {{-- CABECERA --}}
                <div class="border-b bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-6">

                    <div class="flex items-center gap-4">

                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-purple-600 to-fuchsia-600 text-3xl text-white shadow-xl shadow-purple-300 animate-float">
                            🧮
                        </div>

                        <div>
                            <h2 class="text-3xl font-black text-gray-800">
                                Calcula tu préstamo
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Ingresa los datos para visualizar cuotas, intereses y pagos aproximados.
                            </p>
                        </div>

                    </div>

                </div>

                {{-- LIVEWIRE --}}
                <div class="p-8">
                    <div class="rounded-3xl border-t-4 border-purple-600 bg-white p-6 shadow-xl">
                        <livewire:simulador-prestamo />
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>