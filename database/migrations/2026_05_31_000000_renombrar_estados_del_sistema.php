<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->permitirValoresIntermedios();

        DB::table('solicitudes_prestamos')->where('estado', 'pendiente')->update(['estado' => 'solicitado']);
        DB::table('solicitudes_prestamos')->where('estado', 'aprobada')->update(['estado' => 'aprobado']);
        DB::table('solicitudes_prestamos')->where('estado', 'rechazada')->update(['estado' => 'rechazado']);

        DB::table('prestamos')->where('estado', 'pagado')->update(['estado' => 'liquidado']);
        DB::table('prestamos')->where('estado', 'vencido')->update(['estado' => 'en_mora']);

        DB::table('pagos')->where('estado', 'pendiente')->update(['estado' => 'solicitado']);
        DB::table('pagos')->where('estado', 'pagado')->update(['estado' => 'liquidado']);

        $this->aplicarValoresNuevos();
    }

    public function down(): void
    {
        $this->permitirValoresIntermedios();

        DB::table('solicitudes_prestamos')->where('estado', 'solicitado')->update(['estado' => 'pendiente']);
        DB::table('solicitudes_prestamos')->where('estado', 'aprobado')->update(['estado' => 'aprobada']);
        DB::table('solicitudes_prestamos')->where('estado', 'rechazado')->update(['estado' => 'rechazada']);

        DB::table('prestamos')->where('estado', 'liquidado')->update(['estado' => 'pagado']);
        DB::table('prestamos')->where('estado', 'en_mora')->update(['estado' => 'vencido']);

        DB::table('pagos')->where('estado', 'solicitado')->update(['estado' => 'pendiente']);
        DB::table('pagos')->where('estado', 'liquidado')->update(['estado' => 'pagado']);

        $this->aplicarValoresAnteriores();
    }

    private function permitirValoresIntermedios(): void
    {
        if (! $this->usaMysql()) {
            return;
        }

        DB::statement("ALTER TABLE solicitudes_prestamos MODIFY estado ENUM('pendiente', 'solicitado', 'aprobada', 'aprobado', 'rechazada', 'rechazado') NOT NULL DEFAULT 'solicitado'");
        DB::statement("ALTER TABLE prestamos MODIFY estado ENUM('activo', 'pagado', 'liquidado', 'vencido', 'en_mora') NOT NULL DEFAULT 'activo'");
        DB::statement("ALTER TABLE pagos MODIFY estado ENUM('pendiente', 'solicitado', 'pagado', 'liquidado', 'rechazado') NOT NULL DEFAULT 'liquidado'");
    }

    private function aplicarValoresNuevos(): void
    {
        if (! $this->usaMysql()) {
            return;
        }

        DB::statement("ALTER TABLE solicitudes_prestamos MODIFY estado ENUM('solicitado', 'aprobado', 'rechazado') NOT NULL DEFAULT 'solicitado'");
        DB::statement("ALTER TABLE prestamos MODIFY estado ENUM('activo', 'liquidado', 'en_mora') NOT NULL DEFAULT 'activo'");
        DB::statement("ALTER TABLE pagos MODIFY estado ENUM('solicitado', 'liquidado', 'rechazado') NOT NULL DEFAULT 'liquidado'");
    }

    private function aplicarValoresAnteriores(): void
    {
        if (! $this->usaMysql()) {
            return;
        }

        DB::statement("ALTER TABLE solicitudes_prestamos MODIFY estado ENUM('pendiente', 'aprobada', 'rechazada') NOT NULL DEFAULT 'pendiente'");
        DB::statement("ALTER TABLE prestamos MODIFY estado ENUM('activo', 'pagado', 'vencido') NOT NULL DEFAULT 'activo'");
        DB::statement("ALTER TABLE pagos MODIFY estado ENUM('pendiente', 'pagado', 'rechazado') NOT NULL DEFAULT 'pagado'");
    }

    private function usaMysql(): bool
    {
        return in_array(DB::getDriverName(), ['mysql', 'mariadb'], true);
    }
};
