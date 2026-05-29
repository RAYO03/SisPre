<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Credify</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-950 text-white flex items-center justify-center px-20 py-20">

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
                Crea tu cuenta en Credify
            </h2>

            <p class="text-purple-100 mt-6 text-lg leading-8">
                Regístrate para comenzar a administrar tus préstamos
                y pagos desde una plataforma moderna y segura.
            </p>

        </div>


        {{-- FORMULARIO --}}
        <div class="p-10 lg:p-14 flex items-center bg-gray-950">

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
                    Crear cuenta
                </h2>

                <p class="text-gray-400 mt-3 mb-10">
                    Completa tus datos para registrarte
                </p>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>

                        <label for="name"
                               class="block text-sm text-gray-300 mb-2">
                            Nombre completo
                        </label>

                        <input id="name"
                               class="w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-3 focus:border-purple-500 focus:ring-purple-500"
                               type="text"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               autofocus
                               autocomplete="name">

                        <x-input-error :messages="$errors->get('name')" class="mt-2" />

                    </div>

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
                               autocomplete="username">

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />

                    </div>

                    <!-- Phone -->
                    <div>

                        <label for="telefono"
                               class="block text-sm text-gray-300 mb-2">
                            Telefono
                        </label>

                        <input id="telefono"
                               class="w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-3 focus:border-purple-500 focus:ring-purple-500"
                               type="tel"
                               name="telefono"
                               value="{{ old('telefono') }}"
                               maxlength="10"
                               pattern="[0-9]{10}"
                               required
                               autocomplete="tel"
                               placeholder="Ej: 6623446506">

                        <x-input-error :messages="$errors->get('telefono')" class="mt-2" />

                    </div>

                    <!-- Password -->
                    <div>

                        <label for="password"
                               class="block text-sm text-gray-300 mb-2">
                            Contraseña
                        </label>

                        <input id="password"
                               class="w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-3 focus:border-purple-500 focus:ring-purple-500"
                               type="password"
                               name="password"
                               required
                               autocomplete="new-password">

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                    </div>

                    <!-- Confirm Password -->
                    <div>

                        <label for="password_confirmation"
                               class="block text-sm text-gray-300 mb-2">
                            Confirmar contraseña
                        </label>

                        <input id="password_confirmation"
                               class="w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-3 focus:border-purple-500 focus:ring-purple-500"
                               type="password"
                               name="password_confirmation"
                               required
                               autocomplete="new-password">

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

                    </div>

                    <!-- BOTÓN -->
                    <button type="submit"
                            class="w-full bg-purple-600 hover:bg-purple-700 transition py-4 rounded-xl text-white font-bold text-lg shadow-lg">
                        Crear cuenta
                    </button>

                </form>

                <div class="mt-8 text-center">

                    <a href="{{ route('login') }}"
                       class="text-purple-400 hover:text-purple-300 transition">
                        ¿Ya tienes cuenta? Inicia sesión
                    </a>

                </div>

            </div>

        </div>

    </div>

</body>
</html>
