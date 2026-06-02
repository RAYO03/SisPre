@php
    $navBase = 'group relative flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300';
    $navInactive = 'text-purple-100/80 hover:bg-white/10 hover:text-white';
    $navActive = 'bg-purple-500/25 text-white shadow-lg shadow-purple-900/30 ring-1 ring-purple-300/40';
@endphp

<nav
    x-data="{ open: localStorage.getItem('sidebarOpen') !== 'false' }"
    x-init="
        $watch('open', value => {
            localStorage.setItem('sidebarOpen', value);
            window.dispatchEvent(new Event('sidebar-toggled'));
        })
    "
    :class="open ? 'w-72' : 'w-20'"
    class="fixed left-4 top-4 bottom-4 z-50 overflow-hidden rounded-3xl border border-purple-300/20 bg-purple-950/80 text-white shadow-2xl shadow-purple-950/40 backdrop-blur-xl transition-all duration-500 ease-in-out"
>
    <div class="absolute -top-20 -left-20 h-48 w-48 rounded-full bg-purple-500/30 blur-3xl"></div>
    <div class="absolute -bottom-20 -right-20 h-48 w-48 rounded-full bg-fuchsia-500/20 blur-3xl"></div>

    <div class="relative flex h-full flex-col p-3">

        <div class="mb-4 flex items-center" :class="open ? 'justify-between' : 'justify-center'">

            <a
                href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('cliente.dashboard') }}"
                x-show="open"
                x-transition.opacity.duration.300ms
                class="flex items-center gap-3 overflow-hidden"
            >
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white/10">
                    <img src="{{ asset('images/credify-logo3.png') }}"
                         alt="Credify"
                         class="h-9 w-9 object-contain">
                </div>

                <div>
                    <h1 class="text-lg font-bold leading-tight">Credify</h1>
                    <p class="text-xs text-purple-200">
                        {{ auth()->user()->hasRole('admin') ? 'Administrador' : 'Cliente' }}
                    </p>
                </div>
            </a>

            <button
                @click="
                    open = !open;
                    localStorage.setItem('sidebarOpen', open);
                    window.dispatchEvent(new Event('sidebar-toggled'));
                "
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white/5 hover:bg-white/15 transition"
            >
                <svg x-show="open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>

                <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        

        <div class="flex-1 space-y-2 overflow-y-auto pr-1">

            @role('admin')

                @can('admin.dashboard.ver')
                    <a href="{{ route('admin.dashboard') }}" class="{{ $navBase }} {{ request()->routeIs('admin.dashboard') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-6H3v6z"/></svg>
                        <span x-show="open" x-transition.opacity>Dashboard</span>
                    </a>
                @endcan

                @can('admin.solicitudes.ver')
                    <a href="{{ route('admin.solicitudes') }}" class="{{ $navBase }} {{ request()->routeIs('admin.solicitudes*') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M5 4h14v16H5z"/></svg>
                        <span x-show="open" x-transition.opacity>Solicitudes</span>
                    </a>
                @endcan

                @can('admin.clientes.ver')
                    <a href="{{ route('admin.clientes') }}" class="{{ $navBase }} {{ request()->routeIs('admin.clientes*') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z"/></svg>
                        <span x-show="open" x-transition.opacity>Clientes</span>
                    </a>
                @endcan

                @can('admin.prestamos.ver')
                    <a href="{{ route('admin.prestamos') }}" class="{{ $navBase }} {{ request()->routeIs('admin.prestamos*') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.2 0-4 .9-4 2s1.8 2 4 2 4 .9 4 2-1.8 2-4 2m0-10v12"/></svg>
                        <span x-show="open" x-transition.opacity>Prestamos</span>
                    </a>
                @endcan

                @can('admin.pagos.ver')
                    <a href="{{ route('admin.pagos') }}" class="{{ $navBase }} {{ request()->routeIs('admin.pagos*') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/></svg>
                        <span x-show="open" x-transition.opacity>Pagos</span>
                    </a>
                @endcan

                @can('admin.reportes.ver')
                    <a href="{{ route('admin.reportes') }}" class="{{ $navBase }} {{ request()->routeIs('admin.reportes*') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m4 14v-7m4 7V9m4 10V7m4 12V3"/></svg>
                        <span x-show="open" x-transition.opacity>Reportes</span>
                    </a>
                @endcan

            @else

                @can('cliente.dashboard.ver')
                    <a href="{{ route('cliente.dashboard') }}" class="{{ $navBase }} {{ request()->routeIs('cliente.dashboard') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9v9H3v-9z"/></svg>
                        <span x-show="open" x-transition.opacity>Inicio</span>
                    </a>
                @endcan

                @can('cliente.simulador.ver')
                    <a href="{{ route('cliente.simulador') }}" class="{{ $navBase }} {{ request()->routeIs('cliente.simulador') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 3h12v18H6zM9 7h6M9 11h2m4 0h.01M9 15h2m4 0h.01"/></svg>
                        <span x-show="open" x-transition.opacity>Simulador</span>
                    </a>
                @endcan

                @can('cliente.solicitudes.crear')
                    <a href="{{ route('cliente.solicitud') }}" class="{{ $navBase }} {{ request()->routeIs('cliente.solicitud') || request()->routeIs('cliente.solicitud.store') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span x-show="open" x-transition.opacity>Solicitar prestamo</span>
                    </a>
                @endcan

                @can('cliente.solicitudes.ver')
                    <a href="{{ route('cliente.solicitudes') }}" class="{{ $navBase }} {{ request()->routeIs('cliente.solicitudes') || request()->routeIs('cliente.confirmacion') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M5 4h14v16H5z"/></svg>
                        <span x-show="open" x-transition.opacity>Mis solicitudes</span>
                    </a>
                @endcan

                @can('cliente.prestamos.ver')
                    <a href="{{ route('cliente.prestamos') }}" class="{{ $navBase }} {{ request()->routeIs('cliente.prestamos') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.2 0-4 .9-4 2s1.8 2 4 2 4 .9 4 2-1.8 2-4 2m0-10v12"/></svg>
                        <span x-show="open" x-transition.opacity>Mis prestamos</span>
                    </a>
                @endcan

                @can('cliente.estado-cuenta.ver')
                    <a href="{{ route('cliente.estado-cuenta-general') }}" class="{{ $navBase }} {{ request()->routeIs('cliente.estado-cuenta*') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M7 3h10l4 4v14H7zM17 3v5h5M10 13h8M10 17h8"/></svg>
                        <span x-show="open" x-transition.opacity>Estado de cuenta</span>
                    </a>
                @endcan

                @can('cliente.pagos.ver')
                    <a href="{{ route('cliente.pagos') }}" class="{{ $navBase }} {{ request()->routeIs('cliente.pagos*') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/></svg>
                        <span x-show="open" x-transition.opacity>Pagos</span>
                    </a>
                @endcan

                @can('cliente.perfil.ver')
                    <a href="{{ route('cliente.perfil') }}" class="{{ $navBase }} {{ request()->routeIs('cliente.perfil*') ? $navActive : $navInactive }}" :class="open ? 'justify-start' : 'justify-center'">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0"/></svg>
                        <span x-show="open" x-transition.opacity>Perfil</span>
                    </a>
                @endcan

            @endrole
        </div>

        <div class="mt-3 rounded-2xl bg-black/25 p-2">
            <div x-show="open" x-transition.opacity.duration.300ms class="mb-2 flex items-center gap-3 rounded-xl bg-white/10 px-3 py-2">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-500 font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <p class="truncate text-sm font-bold">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-purple-200">
                        {{ auth()->user()->hasRole('admin') ? 'Administrador' : 'Cliente' }}
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    class="flex w-full items-center justify-center gap-3 rounded-xl bg-white/10 px-3 py-3 text-sm font-bold text-white transition hover:bg-red-600"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H9m4 8H5a2 2 0 01-2-2V6a2 2 0 012-2h8"/>
                    </svg>

                    <span x-show="open" x-transition.opacity>Cerrar sesión</span>
                </button>
            </form>
        </div>

    </div>
</nav>