<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'nombre_completo',
        'empresa',
        'nit_ci',
        'correo',
        'telefono',
        'direccion',
    ];
}