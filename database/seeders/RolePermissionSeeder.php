<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
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
            'admin.prestamos.editar',
            'admin.prestamos.eliminar',
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

        Role::findOrCreate('admin', 'web')
            ->syncPermissions($adminPermissions);

        Role::findOrCreate('cliente', 'web')
            ->syncPermissions($clientePermissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
