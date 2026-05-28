<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prestamo extends Model
{
    protected $fillable = [
        'user_id',
        'cliente_id',
        'capital',
        'tasa_anual',
        'plazo_meses',
        'frecuencia',
        'fecha_inicio',
        'fecha_vencimiento',
        'monto_cuota',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_vencimiento' => 'date',
        'capital' => 'decimal:2',
        'tasa_anual' => 'decimal:4',
        'monto_cuota' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function cuotas(): HasMany
    {
        return $this->hasMany(Cuota::class)->orderBy('numero');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function estaEnMora(): bool
    {
        return $this->cuotas()
            ->where('estado', 'vencida')
            ->orWhere('estado', 'parcialmente_pagada')
            ->where('fecha_vencimiento', '<', now())
            ->exists();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}