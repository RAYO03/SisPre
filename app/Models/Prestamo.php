<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Estado;

class Prestamo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'solicitud_prestamo_id',
        'folio',
        'monto_original',
        'monto_total',
        'saldo_pendiente',
        'plazo_meses',
        'tasa_interes',
        'pago_mensual',
        'fecha_inicio',
        'fecha_final',
        'estado'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function solicitud()
    {
        return $this->belongsTo(
            SolicitudPrestamo::class,
            'solicitud_prestamo_id'
        );
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class)->orderBy('numero');
    }

    public function getEstadoLabelAttribute(): string
    {
        return Estado::label($this->estado);
    }
}
