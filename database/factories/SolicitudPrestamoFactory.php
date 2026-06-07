<?php

namespace Database\Factories;

use App\Models\SolicitudPrestamo;
use App\Models\User;
use App\Support\Estado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SolicitudPrestamo>
 */
class SolicitudPrestamoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'user_id' => User::where('role', 'usuario')
                ->inRandomOrder()
                ->first()?->id ?? 1,

            'telefono' => fake()->phoneNumber(),

            'curp' => strtoupper(
                fake()->bothify('????######??????##')
            ),

            'rfc' => strtoupper(
                fake()->bothify('????######???')
            ),

            'fecha_nacimiento' => fake()->date(),

            'direccion' => fake()->address(),

            'ciudad' => fake()->city(),

            'estado_residencia' => fake()->state(),

            'empresa' => fake()->company(),

            'puesto' => fake()->jobTitle(),

            'antiguedad_laboral' =>
                fake()->numberBetween(1, 10) . ' años',

            'ingreso_mensual' =>
                fake()->numberBetween(8000, 50000),

            'tipo_empleo' => fake()->randomElement([
                'Tiempo completo',
                'Medio tiempo',
                'Freelance'
            ]),

            'telefono_trabajo' => fake()->phoneNumber(),

            'monto_solicitado' =>
                fake()->numberBetween(5000, 100000),

            'plazo_meses' => fake()->randomElement([
                6, 12, 18, 24
            ]),

            'frecuencia_pago' => fake()->randomElement([
                'semanal',
                'quincenal',
                'mensual'
            ]),

            'motivo' => fake()->sentence(),

            'referencia1_nombre' => fake()->name(),

            'referencia1_telefono' => fake()->phoneNumber(),

            'referencia1_relacion' => 'Amigo',

            'referencia2_nombre' => fake()->name(),

            'referencia2_telefono' => fake()->phoneNumber(),

            'referencia2_relacion' => 'Familiar',

            'ine' => 'ine.pdf',

            'comprobante_domicilio' => 'domicilio.pdf',

            'comprobante_ingresos' => 'ingresos.pdf',

            'acepta_terminos' => true,

            'autoriza_validacion' => true,

            'estado' => fake()->randomElement([
                Estado::SOLICITADO,
                Estado::APROBADO,
                Estado::RECHAZADO
            ]),
        ];
    }
}
