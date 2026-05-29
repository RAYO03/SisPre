<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Administrador extends Model
{
    use HasFactory;

    protected $table = 'administradores';

    protected $fillable = [
        'user_id',
        'puesto',
        'telefono'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
