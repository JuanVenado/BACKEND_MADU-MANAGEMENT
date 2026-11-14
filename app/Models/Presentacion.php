<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'activo',
        'descripcion',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_presentacion', 'presentacion', 'producto');
    }
}