<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestamo_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('folio_pago')->unique();
            $table->decimal('monto', 10, 2);
            $table->string('metodo_pago');
            $table->date('fecha_pago');
            $table->string('comprobante')->nullable();
            $table->enum('estado', [
                'solicitado',
                'liquidado',
                'rechazado'
            ])->default('liquidado');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pagos');
    }
};
