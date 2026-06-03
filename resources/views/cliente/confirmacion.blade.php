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

        <div class="mx-auto max-w-3xl animate-slide-up">

            <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

                <div class="bg-gradient-to-r from-purple-100 via-fuchsia-50 to-purple-100 px-8 py-10 text-center">

                    <div class="mx-auto mb-6 flex h-28 w-28 items-center justify-center rounded-3xl bg-gradient-to-br from-green-500 to-emerald-600 text-6xl text-white shadow-xl shadow-green-300 animate-float">
                        ✅
                    </div>

                    <span class="inline-flex rounded-full border border-green-300 bg-green-100 px-4 py-2 text-sm font-bold text-green-700">
                        Solicitud completada
                    </span>

                    <h1 class="mt-5 text-5xl font-black bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 bg-clip-text text-transparent">
                        ¡Solicitud enviada!
                    </h1>

                    <p class="mt-3 text-gray-500">
                        Hemos recibido tu solicitud correctamente.
                    </p>

                </div>

                <div class="p-8 text-center">

                    <div class="rounded-3xl border-t-4 border-green-600 bg-white p-6 shadow-xl">

                        <p class="text-sm font-semibold text-gray-500">
                            Folio de solicitud
                        </p>

                        <h2 class="mt-3 text-3xl font-black text-green-600">
                            {{ $solicitud->folio ?? 'CR-2026-000123' }}
                        </h2>

                    </div>

                    <a href="{{ route('cliente.solicitudes') }}"
                       class="mt-8 inline-block rounded-2xl bg-gradient-to-r from-purple-700 via-fuchsia-600 to-purple-800 px-8 py-4 font-bold text-white shadow-lg shadow-purple-300 transition hover:scale-105">
                        Ir a mis solicitudes
                    </a>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>