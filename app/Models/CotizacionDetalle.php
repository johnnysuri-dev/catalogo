<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CotizacionDetalle extends Model
{
    protected $table = 'cotizacion_detalles';

    protected $fillable = [
        'producto_id',
        'nombre_producto',
        'sku',
        'fotografia',
        'precio_unitario',
        'cantidad',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'precio_unitario' => 'decimal:2',
            'cantidad' => 'integer',
            'subtotal' => 'decimal:2',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
