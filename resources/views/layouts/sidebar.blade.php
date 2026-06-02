@php
    $isAdmin = auth()->user()->hasRole('admin');
@endphp

<aside class="w-64 min-h-screen bg-purple-700 text-white p-6">
    <h1 class="text-2xl font-bold mb-8">Credify</h1>

    <nav class="space-y-3">
        @if($isAdmin)
            @can('admin.dashboard.ver')
                <a href="{{ route('admin.dashboard') }}" class="block p-3 rounded hover:bg-purple-800">Dashboard</a>
            @endcan

            @can('admin.solicitudes.ver')
                <a href="{{ route('admin.solicitudes') }}" class="block p-3 rounded hover:bg-purple-800">Solicitudes</a>
            @endcan

            @can('admin.clientes.ver')
                <a href="{{ route('admin.clientes') }}" class="block p-3 rounded hover:bg-purple-800">Clientes</a>
            @endcan

            @can('admin.prestamos.ver')
                <a href="{{ route('admin.prestamos') }}" class="block p-3 rounded hover:bg-purple-800">Prestamos</a>
            @endcan

            @can('admin.pagos.ver')
                <a href="{{ route('admin.pagos') }}" class="block p-3 rounded hover:bg-purple-800">Pagos</a>
            @endcan

            @can('admin.reportes.ver')
                <a href="{{ route('admin.reportes') }}" class="block p-3 rounded hover:bg-purple-800">Reportes</a>
            @endcan
        @else
            @can('cliente.dashboard.ver')
                <a href="{{ route('cliente.dashboard') }}" class="block p-3 rounded hover:bg-purple-800">Dashboard</a>
            @endcan

            @can('cliente.simulador.ver')
                <a href="{{ route('cliente.simulador') }}" class="block p-3 rounded hover:bg-purple-800">Simulador</a>
            @endcan

            @can('cliente.solicitudes.crear')
                <a href="{{ route('cliente.solicitud') }}" class="block p-3 rounded hover:bg-purple-800">Solicitar prestamo</a>
            @endcan

            @can('cliente.solicitudes.ver')
                <a href="{{ route('cliente.solicitudes') }}" class="block p-3 rounded hover:bg-purple-800">Mis solicitudes</a>
            @endcan

            @can('cliente.prestamos.ver')
                <a href="{{ route('cliente.prestamos') }}" class="block p-3 rounded hover:bg-purple-800">Mis prestamos</a>
            @endcan

            @can('cliente.estado-cuenta.ver')
                <a href="{{ route('cliente.estado-cuenta-general') }}" class="block p-3 rounded hover:bg-purple-800">Estado de cuenta</a>
            @endcan

            @can('cliente.pagos.ver')
                <a href="{{ route('cliente.pagos') }}" class="block p-3 rounded hover:bg-purple-800">Pagos</a>
            @endcan
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left p-3 rounded hover:bg-purple-800">
                Cerrar sesion
            </button>
        </form>
    </nav>
</aside>
