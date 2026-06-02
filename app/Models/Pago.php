<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Estado;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'prestamo_id',
        'user_id',
        'folio_pago',
        'monto',
        'interes_moratorio_pagado',
        'interes_ordinario_pagado',
        'capital_pagado',
        'metodo_pago',
        'fecha_pago',
        'comprobante',
        'estado'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }

    public function cuotas()
    {
        return $this->belongsToMany(Cuota::class)
            ->withPivot('monto_aplicado')
            ->withTimestamps();
    }

    public function getEstadoLabelAttribute(): string
    {
        return Estado::label($this->estado);
    }
}
