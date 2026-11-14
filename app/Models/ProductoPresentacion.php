<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoPresentacion extends Model
{
    use HasFactory;

    protected $table = 'producto_presentacion';

    public $timestamps = false;

    protected $fillable = [
        'producto',
        'presentacion',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto');
    }

    public function presentacion()
    {
        return $this->belongsTo(Presentacion::class, 'presentacion');
    }
}