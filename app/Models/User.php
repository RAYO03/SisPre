<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo_usuario',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    public function administrador()
    {
        return $this->hasOne(Administrador::class);
    }

    public function solicitudes()
    {
        return $this->hasMany(SolicitudPrestamo::class);
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
