<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex items-center gap-8">
                <a href="{{ auth()->user()->tipo_usuario === 'admin' ? route('admin.dashboard') : route('cliente.dashboard') }}"
                   class="flex items-center gap-3">
                    <span class="text-2xl font-bold text-purple-700">Credify</span>
                </a>

                @if(auth()->user()->tipo_usuario === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-purple-700">Dashboard</a>
                    <a href="{{ route('admin.solicitudes') }}" class="text-gray-700 hover:text-purple-700">Solicitudes</a>
                    <a href="{{ route('admin.clientes') }}" class="text-gray-700 hover:text-purple-700">Clientes</a>
                    <a href="{{ route('admin.prestamos') }}" class="text-gray-700 hover:text-purple-700">Préstamos</a>
                    <a href="{{ route('admin.pagos') }}" class="text-gray-700 hover:text-purple-700">Pagos</a>
                    <a href="{{ route('admin.reportes') }}" class="text-gray-700 hover:text-purple-700">Reportes</a>
                @else
                    <a href="{{ route('cliente.dashboard') }}" class="text-gray-700 hover:text-purple-700">Inicio</a>
                    <a href="{{ route('cliente.simulador') }}" class="text-gray-700 hover:text-purple-700">Simulador</a>
                    <a href="{{ route('cliente.solicitud') }}" class="text-gray-700 hover:text-purple-700">Solicitar préstamo</a>
                    <a href="{{ route('cliente.solicitudes') }}" class="text-gray-700 hover:text-purple-700">Mis solicitudes</a>
                    <a href="{{ route('cliente.prestamos') }}" class="text-gray-700 hover:text-purple-700">Mis préstamos</a>
                    <a href="{{ route('cliente.pagos') }}" class="text-gray-700 hover:text-purple-700">Pagos</a>
                    <a href="{{ route('cliente.perfil') }}" class="text-gray-700 hover:text-purple-700">Perfil</a>
                @endif
            </div>

            <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                @csrf
                <button class="text-gray-600 hover:text-red-600">
                    Cerrar sesión
                </button>
            </form>

        </div>
    </div>
</nav>