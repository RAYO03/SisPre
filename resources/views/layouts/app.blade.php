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

            <main class="ml-56 p-6">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>