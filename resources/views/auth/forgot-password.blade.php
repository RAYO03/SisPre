<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - Credify</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        @keyframes glow {
            0%, 100% { opacity: .35; transform: scale(1); }
            50% { opacity: .8; transform: scale(1.08); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideLeft {
            from { opacity: 0; transform: translateX(-35px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideRight {
            from { opacity: 0; transform: translateX(35px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .animate-float { animation: float 4s ease-in-out infinite; }
        .animate-glow { animation: glow 5s ease-in-out infinite; }
        .animate-slide-up { animation: slideUp .8s ease-out forwards; }
        .animate-slide-left { animation: slideLeft .8s ease-out forwards; }
        .animate-slide-right { animation: slideRight .8s ease-out forwards; }
    </style>
</head>

<body class="min-h-screen bg-slate-950 text-white flex items-center justify-center px-4 lg:px-10 py-8 overflow-x-hidden">

    {{-- FONDO ANIMADO --}}
    <div class="fixed inset-0 -z-10">
        <div class="absolute top-[-120px] left-[-120px] w-80 h-80 bg-purple-700 rounded-full blur-3xl animate-glow"></div>
        <div class="absolute bottom-[-120px] right-[-120px] w-80 h-80 bg-fuchsia-600 rounded-full blur-3xl animate-glow"></div>
        <div class="absolute top-1/3 left-1/2 w-60 h-60 bg-indigo-600 rounded-full blur-3xl opacity-30 animate-float"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(168,85,247,.18),transparent_35%),linear-gradient(to_bottom,#020617,#0f172a)]"></div>
    </div>

    {{-- CONTENEDOR --}}
    <div class="w-full max-w-4xl bg-white/10 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2 border border-white/10 animate-slide-up">

        {{-- LADO IZQUIERDO --}}
        <div class="hidden lg:flex flex-col justify-center p-8 bg-white/10 border-r border-white/10 relative overflow-hidden animate-slide-left">

            <div class="absolute top-[-80px] right-[-80px] w-52 h-52 bg-purple-600 rounded-full blur-3xl opacity-40"></div>
            <div class="absolute bottom-[-80px] left-[-80px] w-52 h-52 bg-fuchsia-600 rounded-full blur-3xl opacity-40"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-fuchsia-600 flex items-center justify-center shadow-lg shadow-purple-500/40 animate-float">
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
                        <h1 class="text-3xl font-black">
                            Credi<span class="text-purple-300">fy</span>
                        </h1>

                        <p class="text-sm text-purple-200">
                            Sistema de préstamos
                        </p>
                    </div>
                </div>

                <h2 class="text-4xl font-black leading-tight">
                    Recupera el acceso a tu cuenta
                </h2>

                <p class="text-gray-300 mt-5 text-base leading-7">
                    Ingresa tu correo electrónico y te enviaremos un enlace
                    para restablecer tu contraseña de forma segura.
                </p>

                <div class="grid grid-cols-3 gap-2 mt-8">
                    <div class="bg-white/10 border border-white/10 rounded-2xl p-3 text-center backdrop-blur hover:scale-105 transition">
                        <h3 class="text-xl font-black text-purple-300">🔒</h3>
                        <p class="text-[11px] text-gray-400">Seguro</p>
                    </div>

                    <div class="bg-white/10 border border-white/10 rounded-2xl p-3 text-center backdrop-blur hover:scale-105 transition">
                        <h3 class="text-xl font-black text-purple-300">✉️</h3>
                        <p class="text-[11px] text-gray-400">Correo</p>
                    </div>

                    <div class="bg-white/10 border border-white/10 rounded-2xl p-3 text-center backdrop-blur hover:scale-105 transition">
                        <h3 class="text-xl font-black text-purple-300">⚡</h3>
                        <p class="text-[11px] text-gray-400">Rápido</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORMULARIO --}}
        <div class="p-6 lg:p-8 flex items-center bg-slate-950/40 backdrop-blur-xl animate-slide-right">

            <div class="w-full">

                {{-- LOGO MOBILE --}}
                <div class="lg:hidden flex justify-center mb-6">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-fuchsia-600 flex items-center justify-center shadow-lg shadow-purple-500/40 animate-float">
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
                </div>

                <span class="inline-flex bg-purple-500/10 text-purple-300 px-3 py-1.5 rounded-full text-xs border border-purple-400/20 mb-4">
                    Recuperación segura
                </span>

                <h2 class="text-3xl font-black text-white">
                    Recuperar contraseña
                </h2>

                <p class="text-gray-400 mt-2 mb-6 text-sm leading-6">
                    Escribe tu correo y te enviaremos un enlace para restablecerla.
                </p>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    {{-- EMAIL --}}
                    <div>
                        <label for="email" class="block text-sm text-gray-300 mb-1.5">
                            Correo electrónico
                        </label>

                        <input id="email"
                               class="w-full rounded-xl bg-white/10 border border-white/10 text-white px-4 py-2.5 placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500 focus:bg-white/15 transition duration-300"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="correo@ejemplo.com">

                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    {{-- BOTON --}}
                    <button type="submit"
                            class="group w-full bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-700 hover:to-fuchsia-700 transition duration-300 py-3 rounded-xl text-white font-bold text-base shadow-xl shadow-purple-600/30 hover:scale-[1.03]">
                        Enviar enlace
                        <span class="inline-block transition group-hover:translate-x-1">
                            →
                        </span>
                    </button>

                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}"
                       class="text-purple-400 hover:text-purple-300 transition font-semibold text-sm">
                        ← Volver al inicio de sesión
                    </a>
                </div>

            </div>

        </div>

    </div>

</body>
</html>