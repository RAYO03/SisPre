<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->decimal('ingresos_mensuales', 12, 2)->nullable();
            $table->string('referencia_nombre')->nullable();
            $table->string('referencia_telefono')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('clientes');
    }
};