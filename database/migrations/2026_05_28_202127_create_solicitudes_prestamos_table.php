<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solicitudes_prestamos', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Usuario
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            /*
            |--------------------------------------------------------------------------
            | Datos personales
            |--------------------------------------------------------------------------
            */

            $table->string('telefono');
            $table->string('curp')->nullable();
            $table->string('rfc')->nullable();

            $table->date('fecha_nacimiento')->nullable();

            $table->text('direccion');

            $table->string('ciudad');

            $table->string('estado_residencia');

            /*
            |--------------------------------------------------------------------------
            | Información laboral
            |--------------------------------------------------------------------------
            */

            $table->string('empresa')->nullable();

            $table->string('puesto')->nullable();

            $table->string('antiguedad_laboral')->nullable();

            $table->decimal('ingreso_mensual', 10, 2);

            $table->string('tipo_empleo')->nullable();

            $table->string('telefono_trabajo')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Información del préstamo
            |--------------------------------------------------------------------------
            */

            $table->decimal('monto_solicitado', 10, 2);

            $table->integer('plazo_meses');

            $table->enum('frecuencia_pago', [
                'semanal',
                'quincenal',
                'mensual'
            ]);

            $table->text('motivo')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Referencias personales
            |--------------------------------------------------------------------------
            */

            $table->string('referencia1_nombre')->nullable();
            $table->string('referencia1_telefono')->nullable();
            $table->string('referencia1_relacion')->nullable();

            $table->string('referencia2_nombre')->nullable();
            $table->string('referencia2_telefono')->nullable();
            $table->string('referencia2_relacion')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Documentos
            |--------------------------------------------------------------------------
            */

            $table->string('ine')->nullable();

            $table->string('comprobante_domicilio')->nullable();

            $table->string('comprobante_ingresos')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Confirmaciones
            |--------------------------------------------------------------------------
            */

            $table->boolean('acepta_terminos')->default(false);

            $table->boolean('autoriza_validacion')->default(false);

            /*
            |--------------------------------------------------------------------------
            | Estado solicitud
            |--------------------------------------------------------------------------
            */

            $table->enum('estado', [
                'pendiente',
                'aprobado',
                'rechazado'
            ])->default('pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_prestamos');
    }
};
