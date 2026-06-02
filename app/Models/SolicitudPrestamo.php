<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Estado;

class SolicitudPrestamo extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_prestamos';

    protected $fillable = [
        'user_id',
        'folio',
        'monto_solicitado',
        'plazo_meses',
        'tasa_interes',
        'pago_mensual',
        'total_pagar',
        'motivo',
        'ingreso_mensual',
        'tipo_empleo',
        'antiguedad_laboral',
        'estado'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prestamo()
    {
        return $this->hasOne(Prestamo::class);
    }

    public function getEstadoLabelAttribute(): string
    {
        return Estado::label($this->estado);
    }
}
