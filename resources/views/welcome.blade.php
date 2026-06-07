<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Credify</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-18px); }
        }

        @keyframes glow {
            0%, 100% { opacity: .4; transform: scale(1); }
            50% { opacity: .8; transform: scale(1.08); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(35px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-float { animation: float 4s ease-in-out infinite; }
        .animate-glow { animation: glow 5s ease-in-out infinite; }
        .animate-slide-up { animation: slideUp .9s ease-out forwards; }
        .delay-1 { animation-delay: .2s; }
        .delay-2 { animation-delay: .4s; }
        .delay-3 { animation-delay: .6s; }
    </style>
</head>

<body class="bg-slate-950 text-white min-h-screen overflow-x-hidden">

    <div class="fixed inset-0 -z-10">
        <div class="absolute top-[-120px] left-[-120px] w-96 h-96 bg-purple-700 rounded-full blur-3xl animate-glow"></div>
        <div class="absolute bottom-[-120px] right-[-120px] w-96 h-96 bg-fuchsia-600 rounded-full blur-3xl animate-glow"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(168,85,247,.18),transparent_35%),linear-gradient(to_bottom,#020617,#0f172a)]"></div>
    </div>

    {{-- HEADER --}}
    <header class="w-full px-6 lg:px-12 py-5 flex items-center justify-between border-b border-white/10 backdrop-blur-xl bg-white/5 sticky top-0 z-50">

        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-fuchsia-600 flex items-center justify-center shadow-lg shadow-purple-500/40 animate-float">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-extrabold tracking-wide">
                    Credi<span class="text-purple-400">fy</span>
                </h1>
                <p class="text-xs text-gray-400 -mt-1">Finanzas inteligentes</p>
            </div>
        </div>

        @if (Route::has('login'))
            <nav class="flex items-center gap-4">
                @auth
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}"
                           class="bg-purple-600 hover:bg-purple-700 px-5 py-2 rounded-xl font-semibold transition hover:scale-105">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('cliente.dashboard') }}"
                           class="bg-purple-600 hover:bg-purple-700 px-5 py-2 rounded-xl font-semibold transition hover:scale-105">
                            Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="text-gray-300 hover:text-white transition">
                        Iniciar sesión
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="bg-white text-purple-700 hover:bg-purple-100 px-5 py-2 rounded-xl font-bold transition hover:scale-105 shadow-lg">
                            Registrarse
                        </a>
                    @endif
                @endauth
            </nav>
        @endif

    </header>

    {{-- HERO --}}
    <main class="max-w-7xl mx-auto px-6 lg:px-12 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- TEXTO --}}
            <section class="animate-slide-up">

                <span class="inline-flex items-center gap-2 bg-purple-500/10 text-purple-300 px-5 py-2 rounded-full text-sm border border-purple-400/20 shadow-lg shadow-purple-500/10">
                    ✨ Plataforma moderna de préstamos
                </span>

                <h2 class="text-5xl lg:text-7xl font-black mt-8 leading-tight">
                    Controla tus
                    <span class="bg-gradient-to-r from-purple-400 via-fuchsia-400 to-pink-400 bg-clip-text text-transparent">
                        préstamos
                    </span>
                    como nunca antes
                </h2>

                <p class="text-gray-300 text-lg mt-8 leading-8 max-w-xl">
                    Credify te ayuda a gestionar préstamos, pagos y reportes
                    desde una interfaz elegante, rápida y fácil de usar.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 mt-10">
                    <a href="{{ route('login') }}"
                       class="group bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-700 hover:to-fuchsia-700 px-8 py-4 rounded-2xl font-bold transition hover:scale-105 shadow-xl shadow-purple-600/30 text-center">
                        Comenzar ahora
                        <span class="inline-block transition group-hover:translate-x-1">→</span>
                    </a>

                    <a href="#funciones"
                       class="border border-white/15 hover:border-purple-400 bg-white/5 px-8 py-4 rounded-2xl font-bold transition hover:scale-105 text-center">
                        Ver funciones
                    </a>
                </div>

                <div class="grid grid-cols-3 gap-4 mt-12 max-w-xl">
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center backdrop-blur">
                        <h3 class="text-2xl font-black text-purple-300">120+</h3>
                        <p class="text-xs text-gray-400">Clientes</p>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center backdrop-blur">
                        <h3 class="text-2xl font-black text-purple-300">45</h3>
                        <p class="text-xs text-gray-400">Préstamos</p>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center backdrop-blur">
                        <h3 class="text-2xl font-black text-purple-300">$85k</h3>
                        <p class="text-xs text-gray-400">Pagos</p>
                    </div>
                </div>

            </section>

            {{-- PANEL --}}
            <section class="relative animate-slide-up delay-2">

                <div class="absolute -top-8 -right-8 w-32 h-32 bg-purple-600 rounded-full blur-3xl opacity-50"></div>

                <div class="relative bg-white/10 border border-white/10 rounded-[2rem] p-7 shadow-2xl backdrop-blur-xl animate-float">

                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-3xl font-black">Panel Credify</h3>
                            <p class="text-gray-400 mt-1">Resumen financiero</p>
                        </div>

                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-fuchsia-600 flex items-center justify-center shadow-lg shadow-purple-600/30">
                            📊
                        </div>
                    </div>

                    <div class="space-y-5">

                        <div class="bg-slate-950/70 border border-white/10 rounded-2xl p-5 hover:scale-[1.03] transition">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-400">Préstamos aprobados</span>
                                <span class="text-green-400 font-bold">+18%</span>
                            </div>
                            <div class="w-full bg-gray-800 rounded-full h-3">
                                <div class="bg-gradient-to-r from-purple-500 to-fuchsia-500 h-3 rounded-full w-[78%]"></div>
                            </div>
                        </div>

                        <div class="bg-slate-950/70 border border-white/10 rounded-2xl p-5 hover:scale-[1.03] transition">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-400 text-sm">Pagos procesados</p>
                                    <h4 class="text-3xl font-black mt-1">$85,000</h4>
                                </div>
                                <span class="text-4xl">💸</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-purple-600/20 border border-purple-400/20 rounded-2xl p-5 hover:scale-105 transition">
                                <p class="text-gray-300 text-sm">Clientes</p>
                                <h4 class="text-3xl font-black mt-2">120+</h4>
                            </div>

                            <div class="bg-fuchsia-600/20 border border-fuchsia-400/20 rounded-2xl p-5 hover:scale-105 transition">
                                <p class="text-gray-300 text-sm">Activos</p>
                                <h4 class="text-3xl font-black mt-2">45</h4>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

        </div>

    </main>

    {{-- FUNCIONES --}}
    <section id="funciones" class="py-24 border-t border-white/10 bg-white/5">

        <div class="max-w-7xl mx-auto px-6 lg:px-12">

            <div class="text-center mb-16">
                <span class="text-purple-300 font-bold tracking-widest">
                    FUNCIONES
                </span>

                <h2 class="text-4xl lg:text-5xl font-black mt-3">
                    Todo lo que necesitas en un solo lugar
                </h2>

                <p class="text-gray-400 mt-6 max-w-2xl mx-auto text-lg">
                    Herramientas modernas para administrar tu sistema de préstamos
                    de forma rápida, segura y eficiente.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- PRESTAMOS --}}
                <div class="group bg-slate-950/80 border border-white/10 p-8 rounded-3xl transition duration-500 hover:-translate-y-4 hover:border-purple-400 hover:shadow-2xl hover:shadow-purple-600/30">

                    <div class="text-6xl mb-6 group-hover:scale-125 transition duration-500">
                        💳
                    </div>

                    <h3 class="text-3xl font-black mb-4 text-purple-300">
                        Préstamos
                    </h3>

                    <p class="text-gray-400 leading-8 text-lg">
                        Gestiona solicitudes, montos, plazos e intereses
                        desde un solo lugar con total control.
                    </p>

                </div>

                {{-- PAGOS --}}
                <div class="group bg-slate-950/80 border border-white/10 p-8 rounded-3xl transition duration-500 hover:-translate-y-4 hover:border-purple-400 hover:shadow-2xl hover:shadow-purple-600/30">

                    <div class="text-6xl mb-6 group-hover:scale-125 transition duration-500">
                        💰
                    </div>

                    <h3 class="text-3xl font-black mb-4 text-purple-300">
                        Pagos
                    </h3>

                    <p class="text-gray-400 leading-8 text-lg">
                        Registra y controla todos los pagos realizados,
                        manteniendo un historial actualizado en tiempo real.
                    </p>

                </div>

                {{-- REPORTES --}}
                <div class="group bg-slate-950/80 border border-white/10 p-8 rounded-3xl transition duration-500 hover:-translate-y-4 hover:border-purple-400 hover:shadow-2xl hover:shadow-purple-600/30">

                    <div class="text-6xl mb-6 group-hover:scale-125 transition duration-500">
                        📊
                    </div>

                    <h3 class="text-3xl font-black mb-4 text-purple-300">
                        Reportes
                    </h3>

                    <p class="text-gray-400 leading-8 text-lg">
                        Visualiza estadísticas, ingresos, pagos y préstamos
                        mediante reportes dinámicos y gráficos modernos.
                    </p>

                </div>

            </div>

        </div>

    </section>

</body>
</html>