<?php

namespace App\Support;

class PrestamoConfig
{
    public const MONTO_MINIMO = 1000;
    public const MONTO_MAXIMO = 1000000;

    public static function motivos(): array
    {
        return ['Emergencia', 'Negocio', 'Personal'];
    }

    public static function tiposEmpleo(): array
    {
        return ['Empleado', 'Independiente', 'Negocio propio'];
    }

    public static function antiguedadesLaborales(): array
    {
        return [
            'Menos de 6 meses',
            '6 meses a 1 año',
            '1 a 2 años',
            'Más de 2 años',
        ];
    }

    public static function metodosPago(): array
    {
        return ['Transferencia', 'Deposito', 'Efectivo', 'Tarjeta'];
    }
}
