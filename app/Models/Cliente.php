<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $fillable = [
        'nombre', 'apellido', 'email', 'telefono',
        'direccion', 'ingresos_mensuales',
        'referencia_nombre', 'referencia_telefono',
    ];

    public function prestamos(): HasMany
    {
        return $this->hasMany(Prestamo::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }
}