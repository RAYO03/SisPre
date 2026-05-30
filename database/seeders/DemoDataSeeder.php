<?php

namespace Database\Seeders;

use App\Models\Administrador;
use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Models\SolicitudPrestamo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@credify.test'],
            [
                'name' => 'Admin Credify',
                'password' => Hash::make('password'),
                'tipo_usuario' => 'admin',
            ]
        );

        Administrador::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'puesto' => 'Administrador general',
                'telefono' => '6621000000',
            ]
        );

        $clientes = collect([
            [
                'name' => 'Luis Hernandez',
                'email' => 'luis@credify.test',
                'telefono' => '6621000001',
                'fecha_nacimiento' => '1994-03-14',
                'direccion' => 'Av. Reforma 120',
                'ciudad' => 'Hermosillo',
                'estado' => 'Sonora',
            ],
            [
                'name' => 'Mariana Lopez',
                'email' => 'mariana@credify.test',
                'telefono' => '6621000002',
                'fecha_nacimiento' => '1989-09-22',
                'direccion' => 'Calle Naranjo 45',
                'ciudad' => 'Hermosillo',
                'estado' => 'Sonora',
            ],
            [
                'name' => 'Carlos Ramirez',
                'email' => 'carlos@credify.test',
                'telefono' => '6621000003',
                'fecha_nacimiento' => '1998-01-08',
                'direccion' => 'Blvd. Morelos 800',
                'ciudad' => 'Hermosillo',
                'estado' => 'Sonora',
            ],
        ])->map(function (array $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'tipo_usuario' => 'cliente',
                ]
            );

            Cliente::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'telefono' => $data['telefono'],
                    'fecha_nacimiento' => $data['fecha_nacimiento'],
                    'direccion' => $data['direccion'],
                    'ciudad' => $data['ciudad'],
                    'estado' => $data['estado'],
                ]
            );

            return $user;
        });

        $solicitudes = [
            [
                'user' => $clientes[0],
                'folio' => 'SOL-2026-000001',
                'monto_solicitado' => 15000,
                'plazo_meses' => 12,
                'tasa_interes' => 18,
                'motivo' => 'Capital de trabajo',
                'ingreso_mensual' => 28000,
                'tipo_empleo' => 'Empleado',
                'antiguedad_laboral' => '3 anos',
                'estado' => 'pendiente',
            ],
            [
                'user' => $clientes[1],
                'folio' => 'SOL-2026-000002',
                'monto_solicitado' => 22000,
                'plazo_meses' => 18,
                'tasa_interes' => 20,
                'motivo' => 'Gastos personales',
                'ingreso_mensual' => 35000,
                'tipo_empleo' => 'Independiente',
                'antiguedad_laboral' => '5 anos',
                'estado' => 'aprobada',
            ],
            [
                'user' => $clientes[2],
                'folio' => 'SOL-2026-000003',
                'monto_solicitado' => 8000,
                'plazo_meses' => 6,
                'tasa_interes' => 16,
                'motivo' => 'Reparacion de vehiculo',
                'ingreso_mensual' => 18000,
                'tipo_empleo' => 'Empleado',
                'antiguedad_laboral' => '1 ano',
                'estado' => 'rechazada',
            ],
        ];

        foreach ($solicitudes as $index => $data) {
            $totalPagar = round($data['monto_solicitado'] * (1 + ($data['tasa_interes'] / 100)), 2);
            $pagoMensual = round($totalPagar / $data['plazo_meses'], 2);

            $solicitud = SolicitudPrestamo::updateOrCreate(
                ['folio' => $data['folio']],
                [
                    'user_id' => $data['user']->id,
                    'monto_solicitado' => $data['monto_solicitado'],
                    'plazo_meses' => $data['plazo_meses'],
                    'tasa_interes' => $data['tasa_interes'],
                    'pago_mensual' => $pagoMensual,
                    'total_pagar' => $totalPagar,
                    'motivo' => $data['motivo'],
                    'ingreso_mensual' => $data['ingreso_mensual'],
                    'tipo_empleo' => $data['tipo_empleo'],
                    'antiguedad_laboral' => $data['antiguedad_laboral'],
                    'estado' => $data['estado'],
                ]
            );

            if ($data['estado'] !== 'aprobada') {
                continue;
            }

            $prestamo = Prestamo::updateOrCreate(
                ['folio' => 'PR-2026-' . str_pad((string) ($index + 1), 6, '0', STR_PAD_LEFT)],
                [
                    'user_id' => $data['user']->id,
                    'solicitud_prestamo_id' => $solicitud->id,
                    'monto_total' => $totalPagar,
                    'saldo_pendiente' => $totalPagar - $pagoMensual,
                    'plazo_meses' => $data['plazo_meses'],
                    'tasa_interes' => $data['tasa_interes'],
                    'pago_mensual' => $pagoMensual,
                    'fecha_inicio' => now()->subMonth()->toDateString(),
                    'fecha_final' => now()->addMonths($data['plazo_meses'] - 1)->toDateString(),
                    'estado' => 'activo',
                ]
            );

            Pago::updateOrCreate(
                ['folio_pago' => 'PG-2026-000001'],
                [
                    'prestamo_id' => $prestamo->id,
                    'user_id' => $data['user']->id,
                    'monto' => $pagoMensual,
                    'metodo_pago' => 'Transferencia',
                    'fecha_pago' => now()->subDays(5)->toDateString(),
                    'comprobante' => null,
                    'estado' => 'pagado',
                ]
            );
        }
    }
}
