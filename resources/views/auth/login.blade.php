<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Credify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-950 text-white flex items-center justify-center px-20 py-20">

    <div class="w-full max-w-5xl bg-gray-900 rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2 border border-gray-800">

        <div class="hidden lg:flex flex-col justify-center p-12 bg-gradient-to-br from-purple-700 to-purple-950">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center">
                    <svg class="w-9 h-9 text-purple-700" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-4xl font-bold">Credify</h1>
                    <p class="text-purple-200">Sistema de préstamos</p>
                </div>
            </div>

            <h2 class="text-5xl font-extrabold leading-tight">
                Administra tus préstamos de forma moderna
            </h2>

            <p class="text-purple-100 mt-6 text-lg">
                Controla tus pagos, préstamos y reportes desde una sola plataforma.
            </p>
        </div>

        <div class="p-10 lg:p-14">
            <div class="mb-10 text-center lg:text-left">
                <div class="lg:hidden flex justify-center mb-5">
                    <div class="w-16 h-16 rounded-full bg-purple-600 flex items-center justify-center">
                        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <h2 class="text-4xl font-bold">Bienvenido</h2>
                <p class="text-gray-400 mt-2">Inicia sesión en tu cuenta</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm text-gray-300 mb-2">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-3 focus:border-purple-500 focus:ring-purple-500">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="block text-sm text-gray-300 mb-2">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-3 focus:border-purple-500 focus:ring-purple-500">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                               class="rounded bg-gray-800 border-gray-700 text-purple-600 focus:ring-purple-500">
                        <span class="ms-2 text-sm text-gray-400">Recordarme</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-purple-400 hover:text-purple-300">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full bg-purple-600 hover:bg-purple-700 py-3 rounded-xl font-bold text-lg transition">
                    Iniciar sesión
                </button>
            </form>
        </div>

    </div>

</body>
</html>