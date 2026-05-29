<nav class="fixed left-0 top-0 h-screen w-56 bg-gradient-to-b from-slate-950 via-indigo-950 to-purple-950 text-white shadow-2xl z-50">

    <div class="p-4 border-b border-white/10">
        <a href="{{ auth()->user()->tipo_usuario === 'admin' ? route('admin.dashboard') : route('cliente.dashboard') }}"
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
                    {{ auth()->user()->tipo_usuario === 'admin' ? 'Administrador' : 'Cliente' }}
                </p>
            </div>
        </a>
    </div>

    <div class="p-3 space-y-1 pb-36">
        @if(auth()->user()->tipo_usuario === 'admin')

            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition text-sm">
                Dashboard
            </a>

            <a href="{{ route('admin.solicitudes') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition text-sm">
                Solicitudes
            </a>

            <a href="{{ route('admin.clientes') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition text-sm">
                Clientes
            </a>

            <a href="{{ route('admin.prestamos') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition text-sm">
                Préstamos
            </a>

            <a href="{{ route('admin.pagos') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition text-sm">
                Pagos
            </a>

            <a href="{{ route('admin.reportes') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition text-sm">
                Reportes
            </a>

        @else

            <a href="{{ route('cliente.dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition text-sm">
                Inicio
            </a>

            <a href="{{ route('cliente.simulador') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition text-sm">
                Simulador
            </a>

            <a href="{{ route('cliente.solicitud') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition text-sm">
                Solicitar préstamo
            </a>

            <a href="{{ route('cliente.solicitudes') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition text-sm">
                Mis solicitudes
            </a>

            <a href="{{ route('cliente.prestamos') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition text-sm">
                Mis préstamos
            </a>

            <a href="{{ route('cliente.pagos') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition text-sm">
                Pagos
            </a>

            <a href="{{ route('cliente.perfil') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition text-sm">
                Perfil
            </a>

        @endif
    </div>

    <div class="absolute bottom-0 left-0 w-full p-3 border-t border-white/10 bg-indigo-950">
        <p class="text-xs text-purple-100 mb-2 truncate">
            {{ Auth::user()->name }}
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button class="bg-red-600 hover:bg-red-700 px-4 py-1.5 rounded-md font-medium transition text-xs">
                Cerrar sesión
            </button>
        </form>
    </div>

</nav>