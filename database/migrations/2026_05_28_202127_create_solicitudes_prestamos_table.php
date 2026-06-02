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
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('folio')->unique();
            $table->decimal('monto_solicitado', 10, 2);
            $table->integer('plazo_meses');
            $table->decimal('tasa_interes', 5, 2);
            $table->decimal('pago_mensual', 10, 2);
            $table->decimal('total_pagar', 10, 2);
            $table->text('motivo');
            $table->decimal('ingreso_mensual', 10, 2);
            $table->string('tipo_empleo');
            $table->string('antiguedad_laboral');
            $table->enum('estado', [
                'solicitado',
                'aprobado',
                'rechazado'
            ])->default('solicitado');
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
