<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio',
        'cantidad_base',
        'producto',
        'saldo_posterior',
        'motivo',
        'saldo_anterior',
        'tipo',
        'referencia',
        'cantidad',
        'producto_presentacion',
        'usuario',
    ];

    protected $casts = [
        'cantidad_base' => 'decimal:2',
        'saldo_posterior' => 'decimal:2',
        'saldo_anterior' => 'decimal:2',
        'cantidad' => 'decimal:2',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto');
    }

    public function productoPresentacion()
    {
        return $this->belongsTo(ProductoPresentacion::class, 'producto_presentacion');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario');
    }
}