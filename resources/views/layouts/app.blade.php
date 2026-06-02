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
        <div class="min-h-screen">
            @auth
                @include('layouts.navigation')
            @endauth

            @isset($header)
                <header class="ml-64 bg-white/80 backdrop-blur shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            @if (session('success'))
                <div x-data="{ show: true }"
                     x-init="setTimeout(() => show = false, 5000)"
                     x-show="show"
                     x-transition
                     class="fixed right-6 top-6 z-50 flex items-center gap-4 rounded-lg bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg">
                    <span>{{ session('success') }}</span>
                    <button type="button"
                            class="text-lg leading-none text-white/80 hover:text-white"
                            aria-label="Cerrar alerta"
                            @click="show = false">
                        &times;
                    </button>
                </div>
            @endif

            <main class="ml-56 p-6">
                {{ $slot }}
            </main>
        </div>
        @livewireScripts
    </body>
</html>
