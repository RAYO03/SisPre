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
        'telefono' => '6621234567',
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

test('pago desde admin actualmente no actualiza tabla de amortizacion', function () {
    $admin = adminUser();
    $cliente = clienteUser();
    $prestamo = crearPrestamoParaCliente($cliente);
    $primeraCuota = $prestamo->cuotas()->orderBy('numero')->first();

    $this->actingAs($admin)
        ->post(route('admin.pagos.store'), [
            'prestamo_id' => $prestamo->id,
            'monto' => 100,
            'metodo_pago' => 'Efectivo',
        ])
        ->assertRedirect(route('admin.pagos'));

    expect((float) $primeraCuota->refresh()->monto_pagado)->toBe(0.0);
    expect(DB::table('cuota_pago')->count())->toBe(0);
});
