@php
    $navBase = 'block rounded-lg px-4 py-2 text-sm font-medium transition';
    $navInactive = 'text-purple-100/90 hover:bg-white/10 hover:text-white';
    $navActive = 'bg-white text-purple-900 shadow-sm ring-1 ring-white/60';
@endphp

<nav class="fixed left-0 top-0 h-screen w-56 bg-gradient-to-b from-slate-950 via-indigo-950 to-purple-950 text-white shadow-2xl z-50">

    <div class="p-4 border-b border-white/10">
        <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('cliente.dashboard') }}"
           class="flex items-center gap-3">

            <div class="w-11 h-11 flex items-center justify-center">
                <img
                    src="{{ asset('images/credify-logo3.png') }}"
                    alt="Credify"
                    class="w-full h-full object-contain"
                />
            </div>

            <div>
                <h1 class="font-bold text-lg leading-tight">Credify</h1>
                <p class="text-xs text-purple-200 leading-tight">
                    {{ auth()->user()->hasRole('admin') ? 'Administrador' : 'Cliente' }}
                </p>
            </div>
        </a>
    </div>

    <div class="p-3 space-y-1 pb-36">
        @role('admin')
            @can('admin.dashboard.ver')
                <a href="{{ route('admin.dashboard') }}"
                   class="{{ $navBase }} {{ request()->routeIs('admin.dashboard') ? $navActive : $navInactive }}">
                    Dashboard
                </a>
            @endcan

            @can('admin.solicitudes.ver')
                <a href="{{ route('admin.solicitudes') }}"
                   class="{{ $navBase }} {{ request()->routeIs('admin.solicitudes*') ? $navActive : $navInactive }}">
                    Solicitudes
                </a>
            @endcan

            @can('admin.clientes.ver')
                <a href="{{ route('admin.clientes') }}"
                   class="{{ $navBase }} {{ request()->routeIs('admin.clientes*') ? $navActive : $navInactive }}">
                    Clientes
                </a>
            @endcan

            @can('admin.prestamos.ver')
                <a href="{{ route('admin.prestamos') }}"
                   class="{{ $navBase }} {{ request()->routeIs('admin.prestamos*') ? $navActive : $navInactive }}">
                    Prestamos
                </a>
            @endcan

            @can('admin.pagos.ver')
                <a href="{{ route('admin.pagos') }}"
                   class="{{ $navBase }} {{ request()->routeIs('admin.pagos*') ? $navActive : $navInactive }}">
                    Pagos
                </a>
            @endcan

            @can('admin.reportes.ver')
                <a href="{{ route('admin.reportes') }}"
                   class="{{ $navBase }} {{ request()->routeIs('admin.reportes*') ? $navActive : $navInactive }}">
                    Reportes
                </a>
            @endcan
        @else
            @can('cliente.dashboard.ver')
                <a href="{{ route('cliente.dashboard') }}"
                   class="{{ $navBase }} {{ request()->routeIs('cliente.dashboard') ? $navActive : $navInactive }}">
                    Inicio
                </a>
            @endcan

            @can('cliente.simulador.ver')
                <a href="{{ route('cliente.simulador') }}"
                   class="{{ $navBase }} {{ request()->routeIs('cliente.simulador') ? $navActive : $navInactive }}">
                    Simulador
                </a>
            @endcan

            @can('cliente.solicitudes.crear')
                <a href="{{ route('cliente.solicitud') }}"
                   class="{{ $navBase }} {{ request()->routeIs('cliente.solicitud') || request()->routeIs('cliente.solicitud.store') ? $navActive : $navInactive }}">
                    Solicitar prestamo
                </a>
            @endcan

            @can('cliente.solicitudes.ver')
                <a href="{{ route('cliente.solicitudes') }}"
                   class="{{ $navBase }} {{ request()->routeIs('cliente.solicitudes') || request()->routeIs('cliente.confirmacion') ? $navActive : $navInactive }}">
                    Mis solicitudes
                </a>
            @endcan

            @can('cliente.prestamos.ver')
                <a href="{{ route('cliente.prestamos') }}"
                   class="{{ $navBase }} {{ request()->routeIs('cliente.prestamos') ? $navActive : $navInactive }}">
                    Mis prestamos
                </a>
            @endcan

            @can('cliente.estado-cuenta.ver')
                <a href="{{ route('cliente.estado-cuenta-general') }}"
                   class="{{ $navBase }} {{ request()->routeIs('cliente.estado-cuenta*') ? $navActive : $navInactive }}">
                    Estado de cuenta
                </a>
            @endcan

            @can('cliente.pagos.ver')
                <a href="{{ route('cliente.pagos') }}"
                   class="{{ $navBase }} {{ request()->routeIs('cliente.pagos*') ? $navActive : $navInactive }}">
                    Pagos
                </a>
            @endcan

            @can('cliente.perfil.ver')
                <a href="{{ route('cliente.perfil') }}"
                   class="{{ $navBase }} {{ request()->routeIs('cliente.perfil*') ? $navActive : $navInactive }}">
                    Perfil
                </a>
            @endcan
        @endrole
    </div>

    <div class="absolute bottom-0 left-0 w-full p-3 border-t border-white/10 bg-indigo-950">
        <p class="text-xs text-purple-100 mb-2 truncate">
            {{ Auth::user()->name }}
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button class="bg-red-600 hover:bg-red-700 px-4 py-1.5 rounded-md font-medium transition text-xs">
                Cerrar sesion
            </button>
        </form>
    </div>

</nav>
