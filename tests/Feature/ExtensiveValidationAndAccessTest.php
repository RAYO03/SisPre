<?php

use App\Models\Cliente;
use App\Models\Cuota;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Models\SolicitudPrestamo;
use App\Models\User;
use App\Services\AmortizacionService;
use App\Support\Estado;
use App\Support\PrestamoConfig;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function extensiveClienteUser(array $attributes = []): User
{
    $user = User::factory()->create($attributes);
    $user->assignRole('cliente');

    Cliente::create([
        'user_id' => $user->id,
        'telefono' => '662' . str_pad((string) $user->id, 7, '0', STR_PAD_LEFT),
        'fecha_nacimiento' => '1990-01-01',
        'direccion' => 'Calle Test 123',
        'ciudad' => 'Hermosillo',
        'estado' => 'Sonora',
    ]);

    return $user;
}

function extensiveAdminUser(array $attributes = []): User
{
    $user = User::factory()->create($attributes);
    $user->assignRole('admin');

    return $user;
}

function extensivePrestamoParaCliente(User $cliente, array $overrides = []): Prestamo
{
    $amortizacion = app(AmortizacionService::class);
    $capital = (float) ($overrides['monto_original'] ?? 10000);
    $plazo = (int) ($overrides['plazo_meses'] ?? 12);
    $fechaInicio = Carbon::parse($overrides['fecha_inicio'] ?? now()->toDateString());
    $resumen = $amortizacion->generarResumen($capital, $plazo, $fechaInicio);

    $prestamo = Prestamo::create(array_merge([
        'user_id' => $cliente->id,
        'folio' => 'PR-' . now()->year . '-' . str_pad((string) (Prestamo::count() + 1), 6, '0', STR_PAD_LEFT),
        'monto_original' => $capital,
        'monto_total' => $resumen['total_pagar'],
        'saldo_pendiente' => $resumen['total_pagar'],
        'plazo_meses' => $plazo,
        'tasa_interes' => $resumen['tasa_anual'],
        'pago_mensual' => $resumen['pago_mensual'],
        'fecha_inicio' => $fechaInicio->toDateString(),
        'fecha_final' => $fechaInicio->copy()->addMonthsNoOverflow($plazo)->toDateString(),
        'estado' => Estado::ACTIVO,
    ], $overrides));

    $amortizacion->guardarTabla($prestamo);

    return $prestamo->refresh();
}

function extensiveSolicitudParaCliente(User $cliente, array $overrides = []): SolicitudPrestamo
{
    return SolicitudPrestamo::create(array_merge([
        'user_id' => $cliente->id,
        'folio' => 'SOL-' . now()->year . '-' . str_pad((string) (SolicitudPrestamo::count() + 1), 6, '0', STR_PAD_LEFT),
        'monto_solicitado' => 10000,
        'plazo_meses' => 12,
        'tasa_interes' => 19.90,
        'pago_mensual' => 925.87,
        'total_pagar' => 11110.38,
        'motivo' => 'Negocio',
        'ingreso_mensual' => 25000,
        'tipo_empleo' => 'Empleado',
        'antiguedad_laboral' => PrestamoConfig::antiguedadesLaborales()[2],
        'estado' => Estado::SOLICITADO,
    ], $overrides));
}

test('invitados son redirigidos al login en vistas protegidas', function (string $method, string $routeName, array $parameters = []) {
    $this->{$method}(route($routeName, $parameters))
        ->assertRedirect(route('login', absolute: false));
})->with([
    ['get', 'cliente.dashboard'],
    ['get', 'cliente.solicitud'],
    ['post', 'cliente.solicitud.store'],
    ['get', 'admin.dashboard'],
    ['get', 'admin.prestamos.create'],
    ['post', 'admin.pagos.store'],
]);

test('cada rol solo ve sus vistas principales permitidas', function () {
    $cliente = extensiveClienteUser();
    $admin = extensiveAdminUser();

    $this->actingAs($cliente)->get(route('cliente.dashboard'))->assertOk();
    $this->actingAs($cliente)->get(route('cliente.simulador'))->assertOk();
    $this->actingAs($cliente)->get(route('cliente.solicitud'))->assertOk();
    $this->actingAs($cliente)->get(route('cliente.solicitudes'))->assertOk();
    $this->actingAs($cliente)->get(route('cliente.prestamos'))->assertOk();
    $this->actingAs($cliente)->get(route('cliente.estado-cuenta-general'))->assertOk();
    $this->actingAs($cliente)->get(route('cliente.pagos'))->assertOk();
    $this->actingAs($cliente)->get(route('cliente.perfil'))->assertOk();
    $this->actingAs($cliente)->get(route('cliente.perfil.edit'))->assertOk();

    $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    $this->actingAs($admin)->get(route('admin.solicitudes'))->assertOk();
    $this->actingAs($admin)->get(route('admin.clientes'))->assertOk();
    $this->actingAs($admin)->get(route('admin.prestamos'))->assertOk();
    $this->actingAs($admin)->get(route('admin.prestamos.create'))->assertOk();
    $this->actingAs($admin)->get(route('admin.pagos'))->assertOk();
    $this->actingAs($admin)->get(route('admin.pagos.create'))->assertOk();
    $this->actingAs($admin)->get(route('admin.reportes'))->assertOk();
});

test('usuario con rol correcto pero sin permiso especifico recibe forbidden', function () {
    $cliente = extensiveClienteUser();

    Role::findByName('cliente')->revokePermissionTo('cliente.pagos.crear');
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $prestamo = extensivePrestamoParaCliente($cliente);

    $this->actingAs($cliente)
        ->get(route('cliente.pagos.create', $prestamo->id))
        ->assertForbidden();
});

test('admin con permiso de ver prestamos no puede editar ni eliminar sin permisos especificos', function () {
    $admin = extensiveAdminUser();
    $cliente = extensiveClienteUser();
    $prestamo = extensivePrestamoParaCliente($cliente);

    Role::findByName('admin')->syncPermissions([
        'admin.dashboard.ver',
        'admin.prestamos.ver',
    ]);
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->actingAs($admin)
        ->get(route('admin.prestamos.show', $prestamo->id))
        ->assertOk();

    $this->actingAs($admin)
        ->get(route('admin.prestamos.edit', $prestamo->id))
        ->assertForbidden();

    $this->actingAs($admin)
        ->put(route('admin.prestamos.update', $prestamo->id), [
            'user_id' => $cliente->id,
            'monto_original' => 10000,
            'plazo_meses' => 12,
            'fecha_inicio' => now()->toDateString(),
            'estado' => Estado::ACTIVO,
        ])
        ->assertForbidden();

    $this->actingAs($admin)
        ->delete(route('admin.prestamos.destroy', $prestamo->id))
        ->assertForbidden();
});

test('cliente no puede consultar ni pagar prestamos de otro cliente', function () {
    $cliente = extensiveClienteUser();
    $otroCliente = extensiveClienteUser();
    $prestamoAjeno = extensivePrestamoParaCliente($otroCliente);

    $this->actingAs($cliente)
        ->get(route('cliente.estado-cuenta', $prestamoAjeno->id))
        ->assertNotFound();

    $this->actingAs($cliente)
        ->post(route('cliente.pagos.store', $prestamoAjeno->id), [
            'monto' => 100,
            'metodo_pago' => 'Transferencia',
            'fecha_pago' => now()->toDateString(),
            'return_to' => 'pagos',
        ])
        ->assertNotFound();
});

test('solicitud rechaza montos negativos cero fuera de limite y campos invalidos', function () {
    $cliente = extensiveClienteUser();

    $this->actingAs($cliente)
        ->from(route('cliente.solicitud'))
        ->post(route('cliente.solicitud.store'), [
            'monto_solicitado' => -1,
            'plazo_meses' => 5,
            'motivo' => 'Compra no permitida',
            'ingreso_mensual' => 0,
            'tipo_empleo' => 'Temporal',
            'antiguedad_laboral' => 'ayer',
        ])
        ->assertSessionHasErrors([
            'monto_solicitado',
            'plazo_meses',
            'motivo',
            'ingreso_mensual',
            'tipo_empleo',
            'antiguedad_laboral',
        ]);

    $this->actingAs($cliente)
        ->post(route('cliente.solicitud.store'), [
            'monto_solicitado' => PrestamoConfig::MONTO_MAXIMO + 0.01,
            'plazo_meses' => 12,
            'motivo' => 'Negocio',
            'ingreso_mensual' => 25000,
            'tipo_empleo' => 'Empleado',
            'antiguedad_laboral' => PrestamoConfig::antiguedadesLaborales()[2],
        ])
        ->assertSessionHasErrors(['monto_solicitado']);
});

test('admin no puede crear prestamos con montos fechas usuarios o plazos invalidos', function () {
    $admin = extensiveAdminUser();

    $this->actingAs($admin)
        ->from(route('admin.prestamos.create'))
        ->post(route('admin.prestamos.store'), [
            'user_id' => 999999,
            'monto_original' => -100,
            'plazo_meses' => 7,
            'fecha_inicio' => now()->subDay()->toDateString(),
        ])
        ->assertSessionHasErrors([
            'user_id',
            'monto_original',
            'plazo_meses',
            'fecha_inicio',
        ]);

    $this->actingAs($admin)
        ->post(route('admin.prestamos.store'), [
            'user_id' => extensiveClienteUser()->id,
            'monto_original' => 10000,
            'plazo_meses' => 12,
            'fecha_inicio' => '2026-02-30',
        ])
        ->assertSessionHasErrors(['fecha_inicio']);

    expect(Prestamo::count())->toBe(0);
});

test('admin no puede actualizar prestamo con estado monto plazo o fecha invalida', function () {
    $admin = extensiveAdminUser();
    $cliente = extensiveClienteUser();
    $prestamo = extensivePrestamoParaCliente($cliente);

    $this->actingAs($admin)
        ->from(route('admin.prestamos.edit', $prestamo->id))
        ->put(route('admin.prestamos.update', $prestamo->id), [
            'user_id' => $cliente->id,
            'monto_original' => 0,
            'plazo_meses' => 7,
            'fecha_inicio' => 'fecha-rara',
            'estado' => 'cancelado',
        ])
        ->assertSessionHasErrors([
            'monto_original',
            'plazo_meses',
            'fecha_inicio',
            'estado',
        ]);
});

test('pagos de cliente rechazan montos negativos cero excesivos fechas futuras invalidas y campos no permitidos', function (array $payload, array $expectedErrors) {
    $cliente = extensiveClienteUser();
    $prestamo = extensivePrestamoParaCliente($cliente);

    $basePayload = [
        'monto' => 100,
        'metodo_pago' => 'Transferencia',
        'fecha_pago' => now()->toDateString(),
        'return_to' => 'pagos',
    ];

    $this->actingAs($cliente)
        ->from(route('cliente.pagos.create', $prestamo->id))
        ->post(route('cliente.pagos.store', $prestamo->id), array_merge($basePayload, $payload))
        ->assertSessionHasErrors($expectedErrors);
})->with([
    'monto negativo' => [['monto' => -1], ['monto']],
    'monto cero' => [['monto' => 0], ['monto']],
    'monto mayor al saldo' => [['monto' => 999999999], ['monto']],
    'fecha futura' => [['fecha_pago' => now()->addDay()->toDateString()], ['fecha_pago']],
    'fecha invalida' => [['fecha_pago' => '2026-02-30'], ['fecha_pago']],
    'metodo invalido' => [['metodo_pago' => 'Cripto'], ['metodo_pago']],
    'return_to invalido' => [['return_to' => 'admin'], ['return_to']],
]);

test('admin no puede registrar pagos invalidos ni sobre prestamos liquidados', function () {
    $admin = extensiveAdminUser();
    $cliente = extensiveClienteUser();
    $prestamo = extensivePrestamoParaCliente($cliente, ['estado' => Estado::LIQUIDADO]);

    $this->actingAs($admin)
        ->from(route('admin.pagos.create'))
        ->post(route('admin.pagos.store'), [
            'prestamo_id' => $prestamo->id,
            'monto' => -10,
            'metodo_pago' => 'Cheque',
            'fecha_pago' => now()->addDay()->toDateString(),
        ])
        ->assertSessionHasErrors([
            'monto',
            'metodo_pago',
            'fecha_pago',
        ]);

    $this->actingAs($admin)
        ->post(route('admin.pagos.store'), [
            'prestamo_id' => $prestamo->id,
            'monto' => 10,
            'metodo_pago' => 'Efectivo',
            'fecha_pago' => now()->toDateString(),
        ])
        ->assertNotFound();
});

test('perfil de cliente rechaza email duplicado telefono invalido fechas imposibles y menor de edad', function () {
    $cliente = extensiveClienteUser();
    $otroCliente = extensiveClienteUser(['email' => 'ocupado@example.com']);

    $this->actingAs($cliente)
        ->from(route('cliente.perfil.edit'))
        ->patch(route('cliente.perfil.update'), [
            'name' => '',
            'email' => $otroCliente->email,
            'telefono' => '123',
            'fecha_nacimiento' => now()->subYears(17)->toDateString(),
            'direccion' => str_repeat('x', 256),
            'ciudad' => str_repeat('x', 101),
            'estado' => str_repeat('x', 101),
        ])
        ->assertSessionHasErrors([
            'name',
            'email',
            'telefono',
            'fecha_nacimiento',
            'direccion',
            'ciudad',
            'estado',
        ]);

    $this->actingAs($cliente)
        ->patch(route('cliente.perfil.update'), [
            'name' => 'Cliente Valido',
            'email' => 'cliente-valido@example.com',
            'fecha_nacimiento' => '2026-02-30',
        ])
        ->assertSessionHasErrors(['fecha_nacimiento']);
});

test('perfil de cliente rechaza telefono duplicado', function () {
    $cliente = extensiveClienteUser();
    $otroCliente = extensiveClienteUser();

    $this->actingAs($cliente)
        ->from(route('cliente.perfil.edit'))
        ->patch(route('cliente.perfil.update'), [
            'name' => 'Cliente Valido',
            'email' => 'cliente-valido@example.com',
            'telefono' => $otroCliente->cliente->telefono,
            'fecha_nacimiento' => '1990-01-01',
            'direccion' => 'Calle Valida 123',
            'ciudad' => 'Hermosillo',
            'estado' => 'Sonora',
        ])
        ->assertSessionHasErrors(['telefono'])
        ->assertRedirect(route('cliente.perfil.edit'));
});

test('admin no puede ver editar actualizar ni eliminar usuarios que no son clientes', function () {
    $admin = extensiveAdminUser();
    $otroAdmin = extensiveAdminUser();

    $this->actingAs($admin)
        ->get(route('admin.clientes.show', $otroAdmin->id))
        ->assertNotFound();

    $this->actingAs($admin)
        ->get(route('admin.clientes.edit', $otroAdmin->id))
        ->assertNotFound();

    $this->actingAs($admin)
        ->put(route('admin.clientes.update', $otroAdmin->id), [
            'name' => 'No Cliente',
            'email' => 'no-cliente@example.com',
        ])
        ->assertNotFound();

    $this->actingAs($admin)
        ->delete(route('admin.clientes.destroy', $otroAdmin->id))
        ->assertNotFound();
});

test('admin valida campos al editar clientes', function () {
    $admin = extensiveAdminUser();
    $cliente = extensiveClienteUser();
    $otroCliente = extensiveClienteUser(['email' => 'cliente-ocupado@example.com']);

    $this->actingAs($admin)
        ->from(route('admin.clientes.edit', $cliente->id))
        ->put(route('admin.clientes.update', $cliente->id), [
            'name' => '',
            'email' => $otroCliente->email,
            'telefono' => str_repeat('1', 21),
            'fecha_nacimiento' => '2026-02-30',
            'direccion' => str_repeat('x', 256),
            'ciudad' => str_repeat('x', 256),
            'estado' => str_repeat('x', 256),
        ])
        ->assertSessionHasErrors([
            'name',
            'email',
            'telefono',
            'fecha_nacimiento',
            'direccion',
            'ciudad',
            'estado',
        ]);
});

test('admin no puede editar cliente con telefono duplicado', function () {
    $admin = extensiveAdminUser();
    $cliente = extensiveClienteUser();
    $otroCliente = extensiveClienteUser();

    $this->actingAs($admin)
        ->from(route('admin.clientes.edit', $cliente->id))
        ->put(route('admin.clientes.update', $cliente->id), [
            'name' => 'Cliente Valido',
            'email' => 'cliente-valido@example.com',
            'telefono' => $otroCliente->cliente->telefono,
            'fecha_nacimiento' => '1990-01-01',
            'direccion' => 'Calle Valida 123',
            'ciudad' => 'Hermosillo',
            'estado' => 'Sonora',
        ])
        ->assertSessionHasErrors(['telefono'])
        ->assertRedirect(route('admin.clientes.edit', $cliente->id));
});

test('admin no puede editar cliente con fecha de nacimiento menor de edad o anio invalido', function () {
    $admin = extensiveAdminUser();
    $cliente = extensiveClienteUser();

    $payloadValido = [
        'name' => 'Cliente Valido',
        'email' => 'cliente-valido@example.com',
        'telefono' => '6621234567',
        'direccion' => 'Calle Valida 123',
        'ciudad' => 'Hermosillo',
        'estado' => 'Sonora',
    ];

    $this->actingAs($admin)
        ->from(route('admin.clientes.edit', $cliente->id))
        ->put(route('admin.clientes.update', $cliente->id), array_merge($payloadValido, [
            'fecha_nacimiento' => now()->subYears(17)->toDateString(),
        ]))
        ->assertSessionHasErrors(['fecha_nacimiento'])
        ->assertRedirect(route('admin.clientes.edit', $cliente->id));

    $this->actingAs($admin)
        ->from(route('admin.clientes.edit', $cliente->id))
        ->put(route('admin.clientes.update', $cliente->id), array_merge($payloadValido, [
            'fecha_nacimiento' => '13123-04-23',
        ]))
        ->assertSessionHasErrors(['fecha_nacimiento'])
        ->assertRedirect(route('admin.clientes.edit', $cliente->id));
});

test('admin aprueba una solicitud una sola vez y crea prestamo con cuotas', function () {
    $admin = extensiveAdminUser();
    $cliente = extensiveClienteUser();
    $solicitud = extensiveSolicitudParaCliente($cliente);

    $this->actingAs($admin)
        ->post(route('admin.solicitudes.aprobar', $solicitud->id))
        ->assertRedirect();

    $solicitud->refresh();
    $prestamo = Prestamo::where('solicitud_prestamo_id', $solicitud->id)->first();

    expect($solicitud->estado)->toBe(Estado::APROBADO);
    expect($prestamo)->not->toBeNull();
    expect($prestamo->cuotas()->count())->toBe(12);

    $this->actingAs($admin)
        ->post(route('admin.solicitudes.aprobar', $solicitud->id))
        ->assertRedirect()
        ->assertSessionHas('error');

    expect(Prestamo::where('solicitud_prestamo_id', $solicitud->id)->count())->toBe(1);
});

test('admin rechaza una solicitud una sola vez y no crea prestamo', function () {
    $admin = extensiveAdminUser();
    $cliente = extensiveClienteUser();
    $solicitud = extensiveSolicitudParaCliente($cliente);

    $this->actingAs($admin)
        ->post(route('admin.solicitudes.rechazar', $solicitud->id))
        ->assertRedirect();

    expect($solicitud->refresh()->estado)->toBe(Estado::RECHAZADO);
    expect(Prestamo::where('solicitud_prestamo_id', $solicitud->id)->count())->toBe(0);

    $this->actingAs($admin)
        ->post(route('admin.solicitudes.rechazar', $solicitud->id))
        ->assertRedirect()
        ->assertSessionHas('error');
});

test('cliente no puede ver confirmacion de solicitud ajena', function () {
    $cliente = extensiveClienteUser();
    $otroCliente = extensiveClienteUser();
    $solicitudAjena = extensiveSolicitudParaCliente($otroCliente);

    $this->actingAs($cliente)
        ->get(route('cliente.confirmacion', $solicitudAjena->id))
        ->assertNotFound();
});

test('pago tardio se desglosa en orden mora interes y capital sobre la cuota', function () {
    $cliente = extensiveClienteUser();
    $prestamo = extensivePrestamoParaCliente($cliente, [
        'fecha_inicio' => now()->subMonthsNoOverflow(2)->toDateString(),
    ]);
    $primeraCuota = $prestamo->cuotas()->orderBy('numero')->first();
    $mora = $primeraCuota->calcularInteresMoratorio(fechaReferencia: now());
    $monto = round($mora + (float) $primeraCuota->interes + 25, 2);

    $this->actingAs($cliente)
        ->post(route('cliente.pagos.store', $prestamo->id), [
            'monto' => $monto,
            'metodo_pago' => 'Transferencia',
            'fecha_pago' => now()->toDateString(),
            'return_to' => 'pagos',
        ])
        ->assertRedirect(route('cliente.pagos'));

    $pago = Pago::first();
    $primeraCuota->refresh();

    expect((float) $pago->interes_moratorio_pagado)->toBe($mora);
    expect((float) $pago->interes_ordinario_pagado)->toBe((float) $primeraCuota->interes);
    expect((float) $pago->capital_pagado)->toBe(25.0);
    expect((float) $primeraCuota->mora_pagada)->toBe($mora);
    expect((float) $primeraCuota->monto_pagado)->toBe(round((float) $primeraCuota->interes + 25, 2));
    expect($primeraCuota->estado)->toBe(Estado::PARCIALMENTE_PAGADA);
});

test('pago tardio parcial se queda en mora y el siguiente pago cubre mora restante antes de interes', function () {
    $cliente = extensiveClienteUser();
    $prestamo = extensivePrestamoParaCliente($cliente, [
        'fecha_inicio' => now()->subMonthsNoOverflow(4)->toDateString(),
    ]);
    $primeraCuota = $prestamo->cuotas()->orderBy('numero')->first();
    $moraInicial = $primeraCuota->calcularInteresMoratorio(fechaReferencia: now());

    $this->actingAs($cliente)
        ->post(route('cliente.pagos.store', $prestamo->id), [
            'monto' => 10,
            'metodo_pago' => 'Transferencia',
            'fecha_pago' => now()->toDateString(),
            'return_to' => 'pagos',
        ])
        ->assertRedirect(route('cliente.pagos'));

    $primeraCuota->refresh();
    $moraRestante = round($moraInicial - 10, 2);

    $this->actingAs($cliente)
        ->post(route('cliente.pagos.store', $prestamo->id), [
            'monto' => round($moraRestante + 5, 2),
            'metodo_pago' => 'Transferencia',
            'fecha_pago' => now()->toDateString(),
            'return_to' => 'pagos',
        ])
        ->assertRedirect(route('cliente.pagos'));

    $primerPago = Pago::oldest('id')->first();
    $segundoPago = Pago::latest('id')->first();
    $primeraCuota->refresh();

    expect((float) $primerPago->interes_moratorio_pagado)->toBe(10.0);
    expect((float) $primerPago->interes_ordinario_pagado)->toBe(0.0);
    expect((float) $primerPago->capital_pagado)->toBe(0.0);
    expect((float) $segundoPago->interes_moratorio_pagado)->toBe($moraRestante);
    expect((float) $segundoPago->interes_ordinario_pagado)->toBe(5.0);
    expect((float) $segundoPago->capital_pagado)->toBe(0.0);
    expect((float) $primeraCuota->mora_pagada)->toBe($moraInicial);
    expect((float) $primeraCuota->monto_pagado)->toBe(5.0);
});

test('pago al corriente se aplica primero a interes ordinario y luego a capital sin mora', function () {
    $cliente = extensiveClienteUser();
    $prestamo = extensivePrestamoParaCliente($cliente);
    $primeraCuota = $prestamo->cuotas()->orderBy('numero')->first();
    $monto = round((float) $primeraCuota->interes + 50, 2);

    $this->actingAs($cliente)
        ->post(route('cliente.pagos.store', $prestamo->id), [
            'monto' => $monto,
            'metodo_pago' => 'Transferencia',
            'fecha_pago' => now()->toDateString(),
            'return_to' => 'pagos',
        ])
        ->assertRedirect(route('cliente.pagos'));

    $pago = Pago::first();
    $primeraCuota->refresh();

    expect((float) $pago->interes_moratorio_pagado)->toBe(0.0);
    expect((float) $pago->interes_ordinario_pagado)->toBe((float) $primeraCuota->interes);
    expect((float) $pago->capital_pagado)->toBe(50.0);
    expect((float) $primeraCuota->mora_pagada)->toBe(0.0);
    expect((float) $primeraCuota->monto_pagado)->toBe($monto);
});

test('modificar prestamo regenera cuotas con nuevo plazo y recalcula saldos', function () {
    $admin = extensiveAdminUser();
    $cliente = extensiveClienteUser();
    $prestamo = extensivePrestamoParaCliente($cliente, [
        'monto_original' => 10000,
        'plazo_meses' => 12,
    ]);
    $cuotasOriginales = $prestamo->cuotas()->pluck('id')->all();

    $this->actingAs($admin)
        ->put(route('admin.prestamos.update', $prestamo->id), [
            'user_id' => $cliente->id,
            'monto_original' => 20000,
            'plazo_meses' => 24,
            'fecha_inicio' => now()->addDay()->toDateString(),
            'estado' => Estado::ACTIVO,
        ])
        ->assertRedirect(route('admin.prestamos.show', $prestamo->id));

    $prestamo->refresh();

    expect((float) $prestamo->monto_original)->toBe(20000.0);
    expect($prestamo->plazo_meses)->toBe(24);
    expect((float) $prestamo->saldo_pendiente)->toBe((float) $prestamo->monto_total);
    expect($prestamo->cuotas()->count())->toBe(24);
    expect(Cuota::whereIn('id', $cuotasOriginales)->count())->toBe(0);
    expect((float) $prestamo->cuotas()->where('numero', 24)->first()->saldo_restante)->toBe(0.0);
});

test('eliminar prestamo elimina sus cuotas pagos y aplicaciones de pagos', function () {
    $admin = extensiveAdminUser();
    $cliente = extensiveClienteUser();
    $prestamo = extensivePrestamoParaCliente($cliente);

    $this->actingAs($cliente)
        ->post(route('cliente.pagos.store', $prestamo->id), [
            'monto' => 100,
            'metodo_pago' => 'Transferencia',
            'fecha_pago' => now()->toDateString(),
            'return_to' => 'pagos',
        ])
        ->assertRedirect(route('cliente.pagos'));

    expect($prestamo->cuotas()->count())->toBe(12);
    expect(Pago::where('prestamo_id', $prestamo->id)->count())->toBe(1);
    expect(DB::table('cuota_pago')->count())->toBe(1);

    $this->actingAs($admin)
        ->delete(route('admin.prestamos.destroy', $prestamo->id))
        ->assertRedirect(route('admin.prestamos'));

    expect(Prestamo::find($prestamo->id))->toBeNull();
    expect(Cuota::where('prestamo_id', $prestamo->id)->count())->toBe(0);
    expect(Pago::where('prestamo_id', $prestamo->id)->count())->toBe(0);
    expect(DB::table('cuota_pago')->count())->toBe(0);
});

