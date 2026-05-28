<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudPrestamo extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_prestamos';

    protected $fillable = [

        'user_id',

        'telefono',
        'curp',
        'rfc',
        'fecha_nacimiento',
        'direccion',
        'ciudad',
        'estado_residencia',

        'empresa',
        'puesto',
        'antiguedad_laboral',
        'ingreso_mensual',
        'tipo_empleo',
        'telefono_trabajo',

        'monto_solicitado',
        'plazo_meses',
        'frecuencia_pago',
        'motivo',

        'referencia1_nombre',
        'referencia1_telefono',
        'referencia1_relacion',

        'referencia2_nombre',
        'referencia2_telefono',
        'referencia2_relacion',

        'ine',
        'comprobante_domicilio',
        'comprobante_ingresos',

        'acepta_terminos',
        'autoriza_validacion',

        'estado',
    ];

    protected $casts = [

        'fecha_nacimiento' => 'date',

        'ingreso_mensual' => 'decimal:2',

        'monto_solicitado' => 'decimal:2',

        'acepta_terminos' => 'boolean',

        'autoriza_validacion' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}