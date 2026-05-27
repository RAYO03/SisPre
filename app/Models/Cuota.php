<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cuota extends Model
{
    protected $fillable = [
        'prestamo_id', 'numero', 'fecha_vencimiento',
        'capital', 'interes', 'cuota_total',
        'saldo_restante', 'monto_pagado', 'estado',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'capital' => 'decimal:2',
        'interes' => 'decimal:2',
        'cuota_total' => 'decimal:2',
        'saldo_restante' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
    ];

    public function prestamo(): BelongsTo
    {
        return $this->belongsTo(Prestamo::class);
    }

    public function pagos(): BelongsToMany
    {
        return $this->belongsToMany(Pago::class, 'pago_cuota')
                    ->withPivot('monto_aplicado')
                    ->withTimestamps();
    }

    public function getSaldoPendienteAttribute(): float
    {
        return round($this->cuota_total - $this->monto_pagado, 2);
    }

    public function calcularInteresmoratorio(): float
    {
        if ($this->fecha_vencimiento->isFuture() || $this->estado === 'pagada') {
            return 0;
        }
        $diasAtraso = $this->fecha_vencimiento->diffInDays(now());
        $tasaDiaria = ($this->prestamo->tasa_anual / 100) / 365;
        return round($this->saldo_pendiente * $tasaDiaria * $diasAtraso, 2);
    }
}