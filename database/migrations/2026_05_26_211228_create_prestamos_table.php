<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->decimal('capital', 12, 2);
            $table->decimal('tasa_anual', 8, 4); // ej: 24.00 = 24%
            $table->integer('plazo_meses');
            $table->string('frecuencia')->default('mensual'); // mensual
            $table->date('fecha_inicio');
            $table->date('fecha_vencimiento');
            $table->decimal('monto_cuota', 12, 2);
            $table->string('estado')->default('solicitado');
            // estados: solicitado, aprobado, activo, liquidado, en_mora
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('prestamos');
    }
};