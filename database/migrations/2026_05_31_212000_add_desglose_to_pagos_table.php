<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->decimal('interes_moratorio_pagado', 10, 2)->default(0)->after('monto');
            $table->decimal('interes_ordinario_pagado', 10, 2)->default(0)->after('interes_moratorio_pagado');
            $table->decimal('capital_pagado', 10, 2)->default(0)->after('interes_ordinario_pagado');
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn([
                'interes_moratorio_pagado',
                'interes_ordinario_pagado',
                'capital_pagado',
            ]);
        });
    }
};
