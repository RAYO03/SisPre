<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('solicitud_prestamo_id')
                ->nullable()
                ->constrained('solicitudes_prestamos')
                ->nullOnDelete();
            $table->string('folio')->unique();
            $table->decimal('monto_total', 10, 2);
            $table->decimal('saldo_pendiente', 10, 2);
            $table->integer('plazo_meses');
            $table->decimal('tasa_interes', 5, 2);
            $table->decimal('pago_mensual', 10, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_final');
            $table->enum('estado', [
                'activo',
                'liquidado',
                'en_mora'
            ])->default('activo');
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('prestamos');
    }
};
