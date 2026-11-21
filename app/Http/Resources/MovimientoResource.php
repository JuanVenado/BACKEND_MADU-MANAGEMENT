<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovimientoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'folio' => $this->folio,
            'tipo' => $this->tipo,
            'cantidad' => $this->cantidad,
            'cantidad_base' => $this->cantidad_base,
            'saldo_anterior' => $this->saldo_anterior,
            'saldo_posterior' => $this->saldo_posterior,
            'producto' => $this->resource->getRelation('producto')?->nombre,
            'usuario' => $this->resource->getRelation('usuario')?->nombre,
            'fecha' => $this->created_at?->toDateTimeString(),
        ];
    }
}
