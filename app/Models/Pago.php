<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pago extends Model
{
    protected $fillable = [
        'prestamo_id', 'fecha_pago', 'monto',
        'interes_moratorio_pagado', 'interes_ordinario_pagado',
        'capital_pagado', 'notas',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto' => 'decimal:2',
    ];

    public function prestamo(): BelongsTo
    {
        return $this->belongsTo(Prestamo::class);
    }

    public function cuotas(): BelongsToMany
    {
        return $this->belongsToMany(Cuota::class, 'pago_cuota')
                    ->withPivot('monto_aplicado')
                    ->withTimestamps();
    }
}