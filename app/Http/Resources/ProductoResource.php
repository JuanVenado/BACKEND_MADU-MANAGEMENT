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
            'categoria_id' => $this->categoria,
            'categoria' => $this->categoriaRel?->nombre,
            'unidad_id' => $this->unidad,
            'unidad' => $this->unidadRel?->abreviatura,
            'activo' => (bool) $this->activo,
            'stock_min' => $this->stock_min,
            'saldo_actual' => $this->ultimoMovimiento?->saldo_posterior ?? '0.00',
            'presentaciones' => ProductoPresentacionResource::collection($this->whenLoaded('presentacionesAsignadas')),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
