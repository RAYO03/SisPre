<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestamo_id')->constrained('prestamos')->onDelete('cascade');
            $table->date('fecha_pago');
            $table->decimal('monto', 12, 2);
            $table->decimal('interes_moratorio_pagado', 12, 2)->default(0);
            $table->decimal('interes_ordinario_pagado', 12, 2)->default(0);
            $table->decimal('capital_pagado', 12, 2)->default(0);
            $table->string('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pagos');
    }
};