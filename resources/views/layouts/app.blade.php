<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-slate-100">
        <div
            x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false' }"
            x-init="window.addEventListener('sidebar-toggled', () => sidebarOpen = localStorage.getItem('sidebarOpen') !== 'false')"
            class="min-h-screen"
        >
            @auth
                @include('layouts.navigation')
            @endauth

            @isset($header)
                <header
                    class="bg-white/80 backdrop-blur shadow-sm transition-all duration-500"
                    :class="sidebarOpen ? 'ml-80' : 'ml-28'"
                >
                    <div class="max-w-7xl mx-auto py-6 px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            @if (session('success'))
                <div id="flash-success"
                     class="fixed right-6 top-6 z-50 flex items-center gap-4 rounded-lg bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg transition duration-300">
                    <span>{{ session('success') }}</span>
                    <button type="button"
                            class="text-lg leading-none text-white/80 hover:text-white"
                            aria-label="Cerrar alerta"
                            onclick="document.getElementById('flash-success')?.remove()">
                        &times;
                    </button>
                </div>
            @endif

            <main
                class="p-6 transition-all duration-500"
                :class="sidebarOpen ? 'ml-80' : 'ml-28'"
            >
                {{ $slot }}
            </main>
        </div>
        <script>
            setTimeout(() => {
                const alert = document.getElementById('flash-success');

                if (! alert) {
                    return;
                }

                alert.classList.add('opacity-0', 'translate-y-[-0.25rem]');
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        </script>
        @livewireScripts
    </body>
</html>
