<?php

namespace App\Support;

class Estado
{
    public const SOLICITADO = 'solicitado';
    public const APROBADO = 'aprobado';
    public const RECHAZADO = 'rechazado';
    public const ACTIVO = 'activo';
    public const LIQUIDADO = 'liquidado';
    public const EN_MORA = 'en_mora';
    public const PENDIENTE = 'pendiente';
    public const PAGADA = 'pagada';
    public const VENCIDA = 'vencida';
    public const PARCIALMENTE_PAGADA = 'parcialmente_pagada';

    public const LABELS = [
        self::SOLICITADO => 'Solicitado',
        self::APROBADO => 'Aprobado',
        self::RECHAZADO => 'Rechazado',
        self::ACTIVO => 'Activo',
        self::LIQUIDADO => 'Liquidado',
        self::EN_MORA => 'En mora',
        self::PENDIENTE => 'Pendiente',
        self::PAGADA => 'Pagada',
        self::VENCIDA => 'Vencida',
        self::PARCIALMENTE_PAGADA => 'Parcialmente pagada',
    ];

    public static function label(?string $estado): string
    {
        return self::LABELS[$estado] ?? ucfirst((string) $estado);
    }
}
