<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Credify</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-950 text-white min-h-screen">

    {{-- HEADER --}}
    <header class="w-full px-8 py-5 flex items-center justify-between border-b border-gray-800">

        <div class="flex items-center gap-3">

            {{-- LOGO --}}
            <div class="w-12 h-12 rounded-full bg-purple-600 flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="3"
                     viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7"
                          stroke-linecap="round"
                          stroke-linejoin="round"/>
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-bold text-purple-500">
                    Credify
                </h1>

            </div>
        </div>

        {{-- LOGIN / REGISTER --}}
        @if (Route::has('login'))
            <nav class="flex items-center gap-4">

                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="bg-purple-600 hover:bg-purple-700 px-5 py-2 rounded-lg font-semibold transition">
                        Dashboard
                    </a>
                @else

                    <a href="{{ route('login') }}"
                       class="text-gray-300 hover:text-white transition">
                        Iniciar sesión
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="bg-purple-600 hover:bg-purple-700 px-5 py-2 rounded-lg font-semibold transition">
                            Registrarse
                        </a>
                    @endif

                @endauth

            </nav>
        @endif

    </header>


    {{-- HERO --}}
    <main class="max-w-7xl mx-auto px-8 py-20">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- TEXTO --}}
            <div>

                <span class="bg-purple-600/20 text-purple-400 px-4 py-2 rounded-full text-sm border border-purple-500/20">
                    Plataforma financiera moderna
                </span>

                <h2 class="text-5xl lg:text-6xl font-extrabold mt-8 leading-tight">
                    Administra tus
                    <span class="text-purple-500">
                        préstamos
                    </span>
                    de forma rápida y segura
                </h2>

                <p class="text-gray-400 text-lg mt-8 leading-8">
                    Credify te permite gestionar clientes, préstamos,
                    pagos y estados de cuenta desde un solo lugar.
                    Diseñado para brindar control total y una experiencia moderna.
                </p>

                {{-- BOTONES --}}
                <div class="flex gap-4 mt-10">

                    <a href="{{ route('login') }}"
                       class="bg-purple-600 hover:bg-purple-700 px-7 py-4 rounded-xl font-bold transition shadow-lg">
                        Comenzar ahora
                    </a>

                    <a href="#funciones"
                       class="border border-gray-700 hover:border-purple-500 px-7 py-4 rounded-xl font-bold transition">
                        Ver funciones
                    </a>

                </div>

            </div>


            {{-- TARJETA --}}
            <div class="bg-gray-900 border border-gray-800 rounded-3xl p-8 shadow-2xl">

                <div class="flex items-center justify-between mb-8">

                    <div>
                        <h3 class="text-3xl font-bold">
                            Panel Credify
                        </h3>

                        <p class="text-gray-400 mt-2">
                            Control total de tus préstamos
                        </p>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-purple-600 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="3"
                             viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </div>

                </div>


                <div class="space-y-5">

                    <div class="bg-gray-800 rounded-2xl p-5 flex justify-between items-center">
                        <div>
                            <p class="text-gray-400 text-sm">
                                Clientes registrados
                            </p>

                            <h4 class="text-2xl font-bold mt-1">
                                120+
                            </h4>
                        </div>

                        <span class="text-purple-400 text-3xl">
                            👥
                        </span>
                    </div>


                    <div class="bg-gray-800 rounded-2xl p-5 flex justify-between items-center">
                        <div>
                            <p class="text-gray-400 text-sm">
                                Préstamos activos
                            </p>

                            <h4 class="text-2xl font-bold mt-1">
                                45
                            </h4>
                        </div>

                        <span class="text-purple-400 text-3xl">
                            💳
                        </span>
                    </div>


                    <div class="bg-gray-800 rounded-2xl p-5 flex justify-between items-center">
                        <div>
                            <p class="text-gray-400 text-sm">
                                Pagos procesados
                            </p>

                            <h4 class="text-2xl font-bold mt-1">
                                $85,000
                            </h4>
                        </div>

                        <span class="text-purple-400 text-3xl">
                            📈
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </main>


    {{-- FUNCIONES --}}
    <section id="funciones"
             class="bg-gray-900 border-t border-gray-800 py-20">

        <div class="max-w-7xl mx-auto px-8">

            <h2 class="text-4xl font-bold text-center mb-16">
                Funciones principales
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="bg-gray-950 border border-gray-800 p-8 rounded-3xl hover:border-purple-500 transition">

                    <div class="text-5xl mb-5">
                        👥
                    </div>

                    <h3 class="text-2xl font-bold mb-4 text-purple-400">
                        Clientes
                    </h3>

                    <p class="text-gray-400 leading-7">
                        Registra y administra información de clientes fácilmente.
                    </p>

                </div>


                <div class="bg-gray-950 border border-gray-800 p-8 rounded-3xl hover:border-purple-500 transition">

                    <div class="text-5xl mb-5">
                        💳
                    </div>

                    <h3 class="text-2xl font-bold mb-4 text-purple-400">
                        Préstamos
                    </h3>

                    <p class="text-gray-400 leading-7">
                        Lleva el control de montos, intereses y fechas de pago.
                    </p>

                </div>


                <div class="bg-gray-950 border border-gray-800 p-8 rounded-3xl hover:border-purple-500 transition">

                    <div class="text-5xl mb-5">
                        📊
                    </div>

                    <h3 class="text-2xl font-bold mb-4 text-purple-400">
                        Reportes
                    </h3>

                    <p class="text-gray-400 leading-7">
                        Consulta estados de cuenta y reportes financieros en tiempo real.
                    </p>

                </div>

            </div>

        </div>

    </section>

</body>
</html>