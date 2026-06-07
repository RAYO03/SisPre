<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Credify</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-18px);
            }
        }

        @keyframes glow {
            0%, 100% {
                opacity: .35;
                transform: scale(1);
            }
            50% {
                opacity: .8;
                transform: scale(1.08);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(35px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideLeft {
            from {
                opacity: 0;
                transform: translateX(-45px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideRight {
            from {
                opacity: 0;
                transform: translateX(45px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        .animate-glow {
            animation: glow 5s ease-in-out infinite;
        }

        .animate-slide-up {
            animation: slideUp .9s ease-out forwards;
        }

        .animate-slide-left {
            animation: slideLeft .9s ease-out forwards;
        }

        .animate-slide-right {
            animation: slideRight .9s ease-out forwards;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-950 text-white flex items-center justify-center px-6 lg:px-20 py-20 overflow-hidden">

    {{-- FONDO ANIMADO --}}
    <div class="fixed inset-0 -z-10">
        <div class="absolute top-[-120px] left-[-120px] w-96 h-96 bg-purple-700 rounded-full blur-3xl animate-glow"></div>
        <div class="absolute bottom-[-120px] right-[-120px] w-96 h-96 bg-fuchsia-600 rounded-full blur-3xl animate-glow"></div>
        <div class="absolute top-1/3 left-1/2 w-72 h-72 bg-indigo-600 rounded-full blur-3xl opacity-30 animate-float"></div>

        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(168,85,247,.18),transparent_35%),linear-gradient(to_bottom,#020617,#0f172a)]"></div>
    </div>

    {{-- CONTENEDOR --}}
    <div class="w-full max-w-5xl bg-white/10 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2 border border-white/10 animate-slide-up">

        {{-- PANEL IZQUIERDO --}}
        <div class="hidden lg:flex flex-col justify-center p-12 bg-white/10 border-r border-white/10 relative overflow-hidden animate-slide-left">

            <div class="absolute top-[-80px] right-[-80px] w-60 h-60 bg-purple-600 rounded-full blur-3xl opacity-40"></div>
            <div class="absolute bottom-[-80px] left-[-80px] w-60 h-60 bg-fuchsia-600 rounded-full blur-3xl opacity-40"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-fuchsia-600 flex items-center justify-center shadow-lg shadow-purple-500/40 animate-float">
                        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-4xl font-black">
                            Credi<span class="text-purple-300">fy</span>
                        </h1>
                        <p class="text-purple-200">Sistema de préstamos</p>
                    </div>
                </div>

                <h2 class="text-5xl font-black leading-tight">
                    Administra tus préstamos de forma moderna
                </h2>

                <p class="text-gray-300 mt-6 text-lg leading-8">
                    Controla préstamos, pagos y reportes desde una sola plataforma
                    segura, rápida y elegante.
                </p>

                <div class="grid grid-cols-3 gap-4 mt-10">
                    <div class="bg-white/10 border border-white/10 rounded-2xl p-4 text-center backdrop-blur hover:scale-105 transition">
                        <h3 class="text-2xl font-black text-purple-300">45</h3>
                        <p class="text-xs text-gray-400">Préstamos</p>
                    </div>

                    <div class="bg-white/10 border border-white/10 rounded-2xl p-4 text-center backdrop-blur hover:scale-105 transition">
                        <h3 class="text-2xl font-black text-purple-300">$85K</h3>
                        <p class="text-xs text-gray-400">Pagos</p>
                    </div>

                    <div class="bg-white/10 border border-white/10 rounded-2xl p-4 text-center backdrop-blur hover:scale-105 transition">
                        <h3 class="text-2xl font-black text-purple-300">98%</h3>
                        <p class="text-xs text-gray-400">Control</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORMULARIO --}}
        <div class="p-10 lg:p-14 bg-slate-950/40 backdrop-blur-xl animate-slide-right">

            <div class="mb-10 text-center lg:text-left">
                <div class="lg:hidden flex justify-center mb-5">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-fuchsia-600 flex items-center justify-center shadow-lg shadow-purple-500/40 animate-float">
                        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <span class="inline-flex bg-purple-500/10 text-purple-300 px-4 py-2 rounded-full text-sm border border-purple-400/20 mb-5">
                    Acceso seguro
                </span>

                <h2 class="text-4xl font-black">
                    Bienvenido
                </h2>

                <p class="text-gray-400 mt-2">
                    Inicia sesión en tu cuenta
                </p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm text-gray-300 mb-2">
                        Correo electrónico
                    </label>

                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="username"
                           class="w-full rounded-xl bg-white/10 border border-white/10 text-white px-4 py-3 placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500 focus:bg-white/15 transition">

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="block text-sm text-gray-300 mb-2">
                        Contraseña
                    </label>

                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           class="w-full rounded-xl bg-white/10 border border-white/10 text-white px-4 py-3 placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500 focus:bg-white/15 transition">

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between gap-4">
                    <label class="flex items-center">
                        <input id="remember_me"
                               type="checkbox"
                               name="remember"
                               class="rounded bg-white/10 border-white/20 text-purple-600 focus:ring-purple-500">

                        <span class="ms-2 text-sm text-gray-400">
                            Recordarme
                        </span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-purple-400 hover:text-purple-300 transition">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <button type="submit"
                        class="group w-full bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-700 hover:to-fuchsia-700 py-3 rounded-xl font-bold text-lg transition hover:scale-[1.03] shadow-xl shadow-purple-600/30">
                    Iniciar sesión
                    <span class="inline-block transition group-hover:translate-x-1">
                        →
                    </span>
                </button>

                @if (Route::has('register'))
                    <p class="text-center text-sm text-gray-400">
                        ¿No tienes cuenta?
                        <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300 font-semibold">
                            Regístrate aquí
                        </a>
                    </p>
                @endif

            </form>
        </div>

    </div>

</body>
</html>