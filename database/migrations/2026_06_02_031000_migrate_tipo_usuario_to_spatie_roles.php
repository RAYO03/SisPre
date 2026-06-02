<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $adminPermissions = [
            'admin.dashboard.ver',
            'admin.solicitudes.ver',
            'admin.solicitudes.aprobar',
            'admin.solicitudes.rechazar',
            'admin.clientes.ver',
            'admin.prestamos.ver',
            'admin.prestamos.crear',
            'admin.pagos.ver',
            'admin.pagos.crear',
            'admin.reportes.ver',
        ];

        $clientePermissions = [
            'cliente.dashboard.ver',
            'cliente.simulador.ver',
            'cliente.solicitudes.ver',
            'cliente.solicitudes.crear',
            'cliente.prestamos.ver',
            'cliente.estado-cuenta.ver',
            'cliente.pagos.ver',
            'cliente.pagos.crear',
            'cliente.perfil.ver',
            'cliente.perfil.editar',
        ];

        foreach (array_merge($adminPermissions, $clientePermissions) as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->syncPermissions($adminPermissions);

        $clienteRole = Role::findOrCreate('cliente', 'web');
        $clienteRole->syncPermissions($clientePermissions);

        if (Schema::hasColumn('users', 'tipo_usuario')) {
            User::query()
                ->where('tipo_usuario', 'admin')
                ->get()
                ->each(fn (User $user) => $user->assignRole($adminRole));

            User::query()
                ->where('tipo_usuario', 'cliente')
                ->get()
                ->each(fn (User $user) => $user->assignRole($clienteRole));

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('tipo_usuario');
            });
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'tipo_usuario')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('tipo_usuario', ['admin', 'cliente'])
                    ->default('cliente')
                    ->after('password');
            });
        }

        User::query()
            ->get()
            ->each(function (User $user) {
                $user->forceFill([
                    'tipo_usuario' => $user->hasRole('admin') ? 'admin' : 'cliente',
                ])->save();
            });
    }
};
