@php
    $isAdmin = auth()->user()->tipo_usuario === 'admin';
@endphp

<aside class="w-64 min-h-screen bg-purple-700 text-white p-6">
    <h1 class="text-2xl font-bold mb-8">Credify</h1>

    <nav class="space-y-3">
        @if($isAdmin)
            <a href="{{ route('admin.dashboard') }}" class="block p-3 rounded hover:bg-purple-800">Dashboard</a>
            <a href="{{ route('admin.solicitudes') }}" class="block p-3 rounded hover:bg-purple-800">Solicitudes</a>
            <a href="{{ route('admin.clientes') }}" class="block p-3 rounded hover:bg-purple-800">Clientes</a>
            <a href="{{ route('admin.prestamos') }}" class="block p-3 rounded hover:bg-purple-800">Préstamos</a>
            <a href="{{ route('admin.pagos') }}" class="block p-3 rounded hover:bg-purple-800">Pagos</a>
            <a href="{{ route('admin.reportes') }}" class="block p-3 rounded hover:bg-purple-800">Reportes</a>
        @else
            <a href="{{ route('cliente.dashboard') }}" class="block p-3 rounded hover:bg-purple-800">Dashboard</a>
            <a href="{{ route('cliente.simulador') }}" class="block p-3 rounded hover:bg-purple-800">Simulador</a>
            <a href="{{ route('cliente.solicitud') }}" class="block p-3 rounded hover:bg-purple-800">Solicitar préstamo</a>
            <a href="{{ route('cliente.solicitudes') }}" class="block p-3 rounded hover:bg-purple-800">Mis solicitudes</a>
            <a href="{{ route('cliente.prestamos') }}" class="block p-3 rounded hover:bg-purple-800">Mis préstamos</a>
            <a href="{{ route('cliente.pagos') }}" class="block p-3 rounded hover:bg-purple-800">Pagos</a>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left p-3 rounded hover:bg-purple-800">
                Cerrar sesión
            </button>
        </form>
    </nav>
</aside>