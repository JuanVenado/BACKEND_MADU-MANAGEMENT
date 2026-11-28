<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'unidad',
        'categoria',
        'activo',
        'existencial',
        'stock_min',
        'coding',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'existencial' => 'boolean',
        'stock_min' => 'integer',
    ];

    // Renombradas para evitar conflicto con las columnas 'categoria' y 'unidad'
    public function categoriaRel()
    {
        return $this->belongsTo(Categoria::class, 'categoria');
    }

    public function unidadRel()
    {
        return $this->belongsTo(Unidad::class, 'unidad');
    }

    public function proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'producto_proveedor', 'producto', 'proveedor');
    }

    public function presentaciones()
    {
        return $this->belongsToMany(Presentacion::class, 'producto_presentacion', 'producto', 'presentacion');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'producto');
    }

    public function presentacionesAsignadas(): HasMany
    {
        return $this->hasMany(ProductoPresentacion::class, 'producto');
    }

    public function ultimoMovimiento(): HasOne
    {
        return $this->hasOne(Movimiento::class, 'producto')->latestOfMany();
    }
}
