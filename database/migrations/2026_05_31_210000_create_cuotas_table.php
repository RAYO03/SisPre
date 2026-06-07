<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestamo_id')
                ->constrained('prestamos')
                ->onDelete('cascade');
            $table->unsignedInteger('numero');
            $table->date('fecha_vencimiento');
            $table->decimal('capital', 10, 2);
            $table->decimal('interes', 10, 2);
            $table->decimal('cuota_total', 10, 2);
            $table->decimal('saldo_restante', 10, 2);
            $table->decimal('monto_pagado', 10, 2)->default(0);
            $table->enum('estado', [
                'pendiente',
                'pagada',
                'vencida',
                'parcialmente_pagada',
            ])->default('pendiente');
            $table->timestamps();

            $table->unique(['prestamo_id', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
