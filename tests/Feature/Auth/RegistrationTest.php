<?php

use App\Models\Cliente;
use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'telefono' => '6621234567',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('cliente.perfil.edit', absolute: false));
});

test('new users cannot register with duplicate phone', function () {
    $existing = User::factory()->create();

    Cliente::create([
        'user_id' => $existing->id,
        'telefono' => '6621234567',
    ]);

    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'telefono' => '6621234567',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertSessionHasErrors(['telefono']);
});
