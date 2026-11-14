<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoProveedor extends Model
{
    use HasFactory;

    protected $table = 'producto_proveedor';

    public $timestamps = false;

    protected $fillable = [
        'producto',
        'proveedor',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor');
    }
}