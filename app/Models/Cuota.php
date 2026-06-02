<?php

namespace App\Models;

use App\Support\Estado;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'prestamo_id',
        'numero',
        'fecha_vencimiento',
        'capital',
        'interes',
        'cuota_total',
        'saldo_restante',
        'monto_pagado',
        'mora_pagada',
        'estado',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'capital' => 'decimal:2',
        'interes' => 'decimal:2',
        'cuota_total' => 'decimal:2',
        'saldo_restante' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
        'mora_pagada' => 'decimal:2',
    ];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }

    public function getEstadoLabelAttribute(): string
    {
        return Estado::label($this->estado);
    }

    public function getSaldoPendienteAttribute(): float
    {
        return max(0, round((float) $this->cuota_total - (float) $this->monto_pagado, 2));
    }

    public function getDiasAtrasoAttribute(): int
    {
        if ($this->estado === Estado::PAGADA) {
            return 0;
        }

        $fechaVencimiento = Carbon::parse($this->fecha_vencimiento)->startOfDay();
        $hoy = now()->startOfDay();

        return max(0, (int) floor($fechaVencimiento->diffInDays($hoy, false)));
    }

    public function calcularInteresMoratorio(float $tasaDiaria = 0.001): float
    {
        if ($this->estado === Estado::PAGADA) {
            return 0;
        }

        $diasAtraso = $this->dias_atraso;

        if ($diasAtraso <= 0) {
            return 0;
        }

        $moraGenerada = round($this->saldo_pendiente * $tasaDiaria * $diasAtraso, 2);

        return max(0, round($moraGenerada - (float) $this->mora_pagada, 2));
    }
}
