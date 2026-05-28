        <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - Credify</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-950 text-white flex items-center justify-center px-10 py-10">

    <div class="w-full max-w-5xl bg-gray-900 rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2 border border-gray-800">

        {{-- LADO IZQUIERDO --}}
        <div class="hidden lg:flex flex-col justify-center p-12 bg-gradient-to-br from-purple-700 to-purple-950">

            <div class="flex items-center gap-4 mb-10">

                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center">
                    <svg class="w-9 h-9 text-purple-700"
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
                    <h1 class="text-4xl font-bold">
                        Credify
                    </h1>

                    <p class="text-purple-200">
                        Sistema de préstamos
                    </p>
                </div>

            </div>

            <h2 class="text-5xl font-extrabold leading-tight">
                Recupera el acceso a tu cuenta
            </h2>

            <p class="text-purple-100 mt-6 text-lg leading-8">
                Ingresa tu correo electrónico y te enviaremos un enlace
                para restablecer tu contraseña de forma segura.
            </p>

        </div>


        {{-- FORMULARIO --}}
        <div class="p-10 lg:p-14 flex items-center bg-gray-800">

            <div class="w-full">

                {{-- LOGO MOBILE --}}
                <div class="lg:hidden flex justify-center mb-8">

                    <div class="w-16 h-16 rounded-full bg-purple-600 flex items-center justify-center">
                        <svg class="w-9 h-9 text-white"
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

                <h2 class="text-4xl font-bold text-white">
                    Recuperar contraseña
                </h2>

                <p class="text-gray-400 mt-3 mb-10 leading-7">
                    ¿Olvidaste tu contraseña? No te preocupes.
                    Escribe tu correo y te enviaremos un enlace
                    para restablecerla.
                </p>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>

                        <label for="email"
                               class="block text-sm text-gray-300 mb-2">
                            Correo electrónico
                        </label>

                        <input id="email"
                               class="w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-3 focus:border-purple-500 focus:ring-purple-500"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus>

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />

                    </div>

                    <button type="submit"
                            class="w-full bg-purple-600 hover:bg-purple-700 transition py-4 rounded-xl text-white font-bold text-lg shadow-lg">
                        Enviar enlace de recuperación
                    </button>

                </form>

                <div class="mt-8 text-center">

                    <a href="{{ route('login') }}"
                       class="text-purple-400 hover:text-purple-300 transition">
                        ← Volver al inicio de sesión
                    </a>

                </div>

            </div>

        </div>

    </div>

</body>
</html>