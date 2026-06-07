<?php

use App\Models\Cliente;
use App\Models\Cuota;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Models\SolicitudPrestamo;
use App\Models\User;
use App\Services\AmortizacionService;
use App\Support\Estado;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\DB;


beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function clienteUser(array $attributes = []): User
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

function adminUser(array $attributes = []): User
{
    $user = User::factory()->create($attributes);
    $user->assignRole('admin');

    return $user;
}

function crearPrestamoParaCliente(User $cliente, array $overrides = []): Prestamo
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

test('sistema frances genera cuotas y cierra el saldo en cero', function () {
    $tabla = app(AmortizacionService::class)->generarTabla(
        capital: 10000,
        tasaAnual: 19.90,
        plazoMeses: 12,
        fechaInicio: Carbon::parse('2026-06-01')
    );

    expect($tabla)->toHaveCount(12);
    expect($tabla[0]['interes'])->toBe(165.83);
    expect($tabla[0]['capital'])->toBe(760.04);
    expect($tabla[11]['saldo_restante'])->toEqual(0.0);
    expect(round(array_sum(array_column($tabla, 'cuota_total')), 2))->toBe(11110.38);
});

test('resumen indica ajuste final cuando el redondeo cambia la ultima cuota', function () {
    $resumen = app(AmortizacionService::class)->generarResumen(
        capital: 500,
        plazoMeses: 3,
        fechaInicio: Carbon::parse('2026-06-01')
    );

    expect($resumen['pago_mensual'])->toBe(171.69);
    expect($resumen['pago_final'])->toBe(171.70);
    expect($resumen['ajuste_redondeo'])->toBe(0.01);
    expect($resumen['total_pagar'])->toBe(515.08);
    expect(round(array_sum(array_column($resumen['tabla'], 'cuota_total')), 2))->toBe($resumen['total_pagar']);
});

test('cliente solo puede acceder a rutas de cliente y no al panel admin', function () {
    $cliente = clienteUser();

    $this->actingAs($cliente)->get(route('cliente.dashboard'))->assertOk();
    $this->actingAs($cliente)->get(route('admin.dashboard'))->assertForbidden();
});

test('admin no puede acceder a rutas del cliente', function () {
    $admin = adminUser();

    $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    $this->actingAs($admin)->get(route('cliente.dashboard'))->assertForbidden();
});

test('registro envia al cliente a completar perfil', function () {
    $this->post(route('register'), [
        'name' => 'Cliente Nuevo',
        'email' => 'cliente-nuevo@example.com',
        'telefono' => '6621234567',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertRedirect(route('cliente.perfil.edit'));

    $this->assertAuthenticated();
});

test('cliente con perfil incompleto es redirigido a editar perfil', function () {
    $cliente = User::factory()->create();
    $cliente->assignRole('cliente');

    Cliente::create([
        'user_id' => $cliente->id,
        'telefono' => '6621234567',
    ]);

    $this->actingAs($cliente)
        ->get(route('cliente.dashboard'))
        ->assertRedirect(route('cliente.perfil.edit'));

    $this->actingAs($cliente)
        ->get(route('cliente.perfil.edit'))
        ->assertOk();
});

test('solicitud de prestamo valida campos requeridos y opciones permitidas', function () {
    $cliente = clienteUser();

    $this->actingAs($cliente)
        ->from(route('cliente.solicitud'))
        ->post(route('cliente.solicitud.store'), [
            'monto_solicitado' => 500,
            'plazo_meses' => 7,
            'motivo' => 'Texto libre',
            'ingreso_mensual' => 0,
            'tipo_empleo' => 'Otro',
            'antiguedad_laboral' => 'abc',
        ])
        ->assertSessionHasErrors([
            'monto_solicitado',
            'plazo_meses',
            'motivo',
            'ingreso_mensual',
            'tipo_empleo',
            'antiguedad_laboral',
        ]);

    expect(SolicitudPrestamo::count())->toBe(0);
});

test('cliente crea solicitud valida con tasa calculada por el servicio', function () {
    $cliente = clienteUser();

    $this->actingAs($cliente)
        ->post(route('cliente.solicitud.store'), [
            'monto_solicitado' => 10000,
            'plazo_meses' => 12,
            'motivo' => 'Negocio',
            'ingreso_mensual' => 25000,
            'tipo_empleo' => 'Empleado',
            'antiguedad_laboral' => '1 a 2 años',
        ])
        ->assertRedirect(route('cliente.solicitudes'));

    $solicitud = SolicitudPrestamo::first();

    expect($solicitud)->not->toBeNull();
    expect((float) $solicitud->tasa_interes)->toBe(19.90);
    expect($solicitud->estado)->toBe(Estado::SOLICITADO);
});

test('cliente no puede actualizar perfil con fecha de nacimiento menor de edad', function () {
    $cliente = clienteUser();

    $this->actingAs($cliente)
        ->from(route('cliente.perfil.edit'))
        ->patch(route('cliente.perfil.update'), [
            'name' => $cliente->name,
            'email' => $cliente->email,
            'telefono' => '6621234567',
            'fecha_nacimiento' => '2008-11-21',
        ])
        ->assertSessionHasErrors([
            'fecha_nacimiento' => 'Solo se aceptan fechas de nacimiento de usuarios mayores de edad.',
        ])
        ->assertRedirect(route('cliente.perfil.edit'));
});

test('cliente no puede actualizar perfil con fecha de nacimiento invalida', function () {
    $cliente = clienteUser();

    $this->actingAs($cliente)
        ->from(route('cliente.perfil.edit'))
        ->patch(route('cliente.perfil.update'), [
            'name' => $cliente->name,
            'email' => $cliente->email,
            'telefono' => '6621234567',
            'fecha_nacimiento' => '321831-04-23',
        ])
        ->assertSessionHasErrors(['fecha_nacimiento'])
        ->assertRedirect(route('cliente.perfil.edit'));
});

test('cliente puede actualizar perfil con fecha de nacimiento mayor de edad', function () {
    $cliente = clienteUser();

    $this->actingAs($cliente)
        ->patch(route('cliente.perfil.update'), [
            'name' => $cliente->name,
            'email' => $cliente->email,
            'telefono' => '6621234567',
            'fecha_nacimiento' => now()->subYears(18)->subDay()->format('Y-m-d'),
            'direccion' => 'Calle Nueva 456',
            'ciudad' => 'Hermosillo',
            'estado' => 'Sonora',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('cliente.perfil'));

    expect($cliente->cliente->refresh()->fecha_nacimiento)
        ->toBe(now()->subYears(18)->subDay()->format('Y-m-d'));
});

test('solicitud de prestamo rechaza monto mayor al maximo permitido', function () {
    $cliente = clienteUser();

    $this->actingAs($cliente)
        ->from(route('cliente.solicitud'))
        ->post(route('cliente.solicitud.store'), [
            'monto_solicitado' => 1000000.01,
            'plazo_meses' => 12,
            'motivo' => 'Negocio',
            'ingreso_mensual' => 25000,
            'tipo_empleo' => 'Empleado',
            'antiguedad_laboral' => '1 a 2 años',
        ])
        ->assertSessionHasErrors(['monto_solicitado']);

    expect(SolicitudPrestamo::count())->toBe(0);
});

test('admin crea prestamo activo con tabla de amortizacion', function () {
    $admin = adminUser();
    $cliente = clienteUser();

    $this->actingAs($admin)
        ->post(route('admin.prestamos.store'), [
            'user_id' => $cliente->id,
            'monto_original' => 15000,
            'plazo_meses' => 12,
            'fecha_inicio' => now()->toDateString(),
        ])
        ->assertRedirect();

    $prestamo = Prestamo::first();

    expect($prestamo)->not->toBeNull();
    expect((float) $prestamo->monto_original)->toBe(15000.0);
    expect($prestamo->estado)->toBe(Estado::ACTIVO);
    expect($prestamo->cuotas()->count())->toBe(12);
    expect((float) $prestamo->cuotas()->where('numero', 12)->first()->saldo_restante)->toBe(0.0);
});

test('admin ve prestamos activos en mora y liquidados en el listado', function () {
    $admin = adminUser();
    $cliente = clienteUser();

    $activo = crearPrestamoParaCliente($cliente);
    $enMora = crearPrestamoParaCliente($cliente, ['estado' => Estado::EN_MORA]);
    $liquidado = crearPrestamoParaCliente($cliente, [
        'estado' => Estado::LIQUIDADO,
        'saldo_pendiente' => 0,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.prestamos'))
        ->assertOk()
        ->assertSee($activo->folio)
        ->assertSee($enMora->folio)
        ->assertSee($liquidado->folio)
        ->assertSee('Activo')
        ->assertSee('En mora')
        ->assertSee('Liquidado');
});

test('admin no puede editar ni eliminar prestamos liquidados', function () {
    $admin = adminUser();
    $cliente = clienteUser();
    $prestamo = crearPrestamoParaCliente($cliente, [
        'estado' => Estado::LIQUIDADO,
        'saldo_pendiente' => 0,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.prestamos.edit', $prestamo->id))
        ->assertForbidden();

    $this->actingAs($admin)
        ->delete(route('admin.prestamos.destroy', $prestamo->id))
        ->assertForbidden();

    expect($prestamo->fresh())->not->toBeNull();
});

test('admin puede editar prestamos en mora', function () {
    $admin = adminUser();
    $cliente = clienteUser();
    $prestamo = crearPrestamoParaCliente($cliente, ['estado' => Estado::EN_MORA]);

    $this->actingAs($admin)
        ->get(route('admin.prestamos.edit', $prestamo->id))
        ->assertOk();
});

test('admin no puede crear prestamo para un usuario sin rol cliente', function () {
    $admin = adminUser();
    $otroAdmin = adminUser();

    $this->actingAs($admin)
        ->post(route('admin.prestamos.store'), [
            'user_id' => $otroAdmin->id,
            'monto_original' => 15000,
            'plazo_meses' => 12,
            'fecha_inicio' => now()->toDateString(),
        ])
        ->assertNotFound();

    expect(Prestamo::count())->toBe(0);
});

test('pago parcial del cliente se aplica primero al interes ordinario', function () {
    $cliente = clienteUser();
    $prestamo = crearPrestamoParaCliente($cliente);
    $primeraCuota = $prestamo->cuotas()->orderBy('numero')->first();

    $this->actingAs($cliente)
        ->post(route('cliente.pagos.store', $prestamo->id), [
            'monto' => 50,
            'metodo_pago' => 'Transferencia',
            'fecha_pago' => now()->toDateString(),
            'return_to' => 'pagos',
        ])
        ->assertRedirect(route('cliente.pagos'));

    $pago = Pago::first();
    $primeraCuota->refresh();

    expect((float) $pago->interes_moratorio_pagado)->toBe(0.0);
    expect((float) $pago->interes_ordinario_pagado)->toBe(50.0);
    expect((float) $pago->capital_pagado)->toBe(0.0);
    expect((float) $primeraCuota->monto_pagado)->toBe(50.0);
    expect($primeraCuota->estado)->toBe(Estado::PARCIALMENTE_PAGADA);
});

test('mis pagos muestra el total real aunque este paginado', function () {
    $cliente = clienteUser();
    $prestamo = crearPrestamoParaCliente($cliente);

    foreach (range(1, 11) as $numero) {
        Pago::create([
            'prestamo_id' => $prestamo->id,
            'user_id' => $cliente->id,
            'folio_pago' => 'PG-TEST-' . str_pad((string) $numero, 3, '0', STR_PAD_LEFT),
            'monto' => 100,
            'interes_moratorio_pagado' => 0,
            'interes_ordinario_pagado' => 10,
            'capital_pagado' => 90,
            'metodo_pago' => 'Transferencia',
            'fecha_pago' => now()->toDateString(),
            'estado' => Estado::LIQUIDADO,
        ]);
    }

    $this->actingAs($cliente)
        ->get(route('cliente.pagos'))
        ->assertOk()
        ->assertSee('Total: 11');
});

test('pago mayor a una cuota se reparte hacia la siguiente cuota', function () {
    $cliente = clienteUser();
    $prestamo = crearPrestamoParaCliente($cliente);
    $primeraCuota = $prestamo->cuotas()->orderBy('numero')->first();

    $this->actingAs($cliente)
        ->post(route('cliente.pagos.store', $prestamo->id), [
            'monto' => round((float) $primeraCuota->cuota_total + 10, 2),
            'metodo_pago' => 'Transferencia',
            'fecha_pago' => now()->toDateString(),
            'return_to' => 'pagos',
        ])
        ->assertRedirect(route('cliente.pagos'));

    $primeraCuota->refresh();
    $segundaCuota = $prestamo->cuotas()->where('numero', 2)->first();

    expect($primeraCuota->estado)->toBe(Estado::PAGADA);
    expect((float) $segundaCuota->monto_pagado)->toBe(10.0);
    expect($segundaCuota->estado)->toBe(Estado::PARCIALMENTE_PAGADA);
    expect(DB::table('cuota_pago')->count())->toBe(2);
});

test('pago tardio se aplica primero a mora antes que a cuota normal', function () {
    $cliente = clienteUser();
    $prestamo = crearPrestamoParaCliente($cliente, [
        'fecha_inicio' => now()->subMonthsNoOverflow(4)->toDateString(),
    ]);

    $this->actingAs($cliente)
        ->post(route('cliente.pagos.store', $prestamo->id), [
            'monto' => 10,
            'metodo_pago' => 'Transferencia',
            'fecha_pago' => now()->toDateString(),
            'return_to' => 'pagos',
        ])
        ->assertRedirect(route('cliente.pagos'));

    $pago = Pago::first();
    $primeraCuota = $prestamo->cuotas()->orderBy('numero')->first();

    expect((float) $pago->interes_moratorio_pagado)->toBe(10.0);
    expect((float) $pago->interes_ordinario_pagado)->toBe(0.0);
    expect((float) $pago->capital_pagado)->toBe(0.0);
    expect((float) $primeraCuota->refresh()->mora_pagada)->toBe(10.0);
    expect((float) $primeraCuota->monto_pagado)->toBe(0.0);
});

test('cliente puede liquidar saldo completo incluyendo mora pendiente', function () {
    $cliente = clienteUser();
    $prestamo = crearPrestamoParaCliente($cliente);
    $primeraCuota = $prestamo->cuotas()->orderBy('numero')->first();

    $primeraCuota->update([
        'fecha_vencimiento' => now()->subDays(10)->toDateString(),
        'estado' => Estado::VENCIDA,
    ]);

    $prestamo->refresh()->load('cuotas');
    $totalConMora = round($prestamo->cuotas->sum(
        fn (Cuota $cuota) => $cuota->saldo_pendiente + $cuota->calcularInteresMoratorio()
    ), 2);

    $this->actingAs($cliente)
        ->post(route('cliente.pagos.store', $prestamo->id), [
            'monto' => $totalConMora,
            'metodo_pago' => 'Transferencia',
            'fecha_pago' => now()->toDateString(),
            'return_to' => 'pagos',
        ])
        ->assertRedirect(route('cliente.pagos'));

    $pago = Pago::first();
    $prestamo->refresh();

    expect((float) $pago->interes_moratorio_pagado)->toBeGreaterThan(0);
    expect((float) $prestamo->saldo_pendiente)->toBe(0.0);
    expect($prestamo->estado)->toBe(Estado::LIQUIDADO);
    expect($prestamo->cuotas()->where('estado', Estado::PARCIALMENTE_PAGADA)->count())->toBe(0);
    expect($prestamo->cuotas()->where('estado', Estado::PAGADA)->count())->toBe($prestamo->plazo_meses);
});

test('admin registra pago y actualiza tabla de amortizacion', function () {
    $admin = adminUser();
    $cliente = clienteUser();
    $prestamo = crearPrestamoParaCliente($cliente);
    $primeraCuota = $prestamo->cuotas()->orderBy('numero')->first();

    $this->actingAs($admin)
        ->post(route('admin.pagos.store'), [
            'prestamo_id' => $prestamo->id,
            'monto' => 100,
            'metodo_pago' => 'Efectivo',
            'fecha_pago' => now()->toDateString(),
        ])
        ->assertRedirect(route('admin.pagos'));

    $pago = Pago::first();
    $primeraCuota->refresh();

    expect((float) $pago->interes_ordinario_pagado)->toBe(100.0);
    expect((float) $pago->capital_pagado)->toBe(0.0);
    expect((float) $primeraCuota->monto_pagado)->toBe(100.0);
    expect($primeraCuota->estado)->toBe(Estado::PARCIALMENTE_PAGADA);
    expect(DB::table('cuota_pago')->count())->toBe(1);
});

test('admin puede liquidar prestamo incluyendo mora pendiente', function () {
    $admin = adminUser();
    $cliente = clienteUser();
    $prestamo = crearPrestamoParaCliente($cliente);
    $primeraCuota = $prestamo->cuotas()->orderBy('numero')->first();

    $primeraCuota->update([
        'fecha_vencimiento' => now()->subDays(10)->toDateString(),
        'estado' => Estado::VENCIDA,
    ]);

    $prestamo->refresh()->load('cuotas');
    $totalConMora = round($prestamo->cuotas->sum(
        fn (Cuota $cuota) => $cuota->saldo_pendiente + $cuota->calcularInteresMoratorio()
    ), 2);

    $this->actingAs($admin)
        ->post(route('admin.pagos.store'), [
            'prestamo_id' => $prestamo->id,
            'monto' => $totalConMora,
            'metodo_pago' => 'Transferencia',
            'fecha_pago' => now()->toDateString(),
        ])
        ->assertRedirect(route('admin.pagos'));

    $pago = Pago::first();
    $prestamo->refresh();

    expect((float) $pago->interes_moratorio_pagado)->toBeGreaterThan(0);
    expect((float) $prestamo->saldo_pendiente)->toBe(0.0);
    expect($prestamo->estado)->toBe(Estado::LIQUIDADO);
    expect($prestamo->cuotas()->where('estado', Estado::PARCIALMENTE_PAGADA)->count())->toBe(0);
});
