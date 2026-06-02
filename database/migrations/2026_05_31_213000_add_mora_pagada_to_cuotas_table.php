<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuotas', function (Blueprint $table) {
            $table->decimal('mora_pagada', 10, 2)->default(0)->after('monto_pagado');
        });
    }

    public function down(): void
    {
        Schema::table('cuotas', function (Blueprint $table) {
            $table->dropColumn('mora_pagada');
        });
    }
};
