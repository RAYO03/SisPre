<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuota_pago', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuota_id')
                ->constrained('cuotas')
                ->onDelete('cascade');
            $table->foreignId('pago_id')
                ->constrained('pagos')
                ->onDelete('cascade');
            $table->decimal('monto_aplicado', 10, 2);
            $table->timestamps();

            $table->unique(['cuota_id', 'pago_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuota_pago');
    }
};
