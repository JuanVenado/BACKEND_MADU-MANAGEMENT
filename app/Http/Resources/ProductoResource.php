<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'coding' => $this->coding,
            'categoria' => $this->categoriaRel?->nombre,
            'unidad' => $this->unidadRel?->abreviatura,
            'activo' => (bool) $this->activo,
            'stock_min' => $this->stock_min,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}