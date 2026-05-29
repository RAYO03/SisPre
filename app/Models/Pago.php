<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'prestamo_id',
        'user_id',
        'folio_pago',
        'monto',
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
}
