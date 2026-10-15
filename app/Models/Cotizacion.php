<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = [
        'codigo',
        'nombre_cliente',
        'empresa',
        'correo',
        'telefono',
        'mensaje',
        'moneda',
        'total',
        'estado',
        'user_id',
    ];

    protected function casts(): array
    {
        return ['total' => 'decimal:2'];
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(CotizacionDetalle::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_usuario');
    }
}
