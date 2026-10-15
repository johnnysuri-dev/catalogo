<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'sku',
        'categoria_id',
        'descripcion',
        'fotos',
        'precio',
        'stock',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'stock' => 'integer',
            'activo' => 'boolean',
            'fotos' => 'array',
        ];
    }

    public function detallesCotizacion(): HasMany
    {
        return $this->hasMany(CotizacionDetalle::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
