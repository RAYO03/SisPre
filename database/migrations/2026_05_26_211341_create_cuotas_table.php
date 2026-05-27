<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestamo_id')->constrained('prestamos')->onDelete('cascade');
            $table->integer('numero');
            $table->date('fecha_vencimiento');
            $table->decimal('capital', 12, 2);
            $table->decimal('interes', 12, 2);
            $table->decimal('cuota_total', 12, 2);
            $table->decimal('saldo_restante', 12, 2);
            $table->decimal('monto_pagado', 12, 2)->default(0);
            $table->string('estado')->default('pendiente');
            // estados: pendiente, pagada, vencida, parcialmente_pagada
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('cuotas');
    }
};