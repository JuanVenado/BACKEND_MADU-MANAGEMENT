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
            'producto' => $this->producto?->nombre,
            'usuario' => $this->usuario?->nombre,
            'fecha' => $this->created_at?->toDateTimeString(),
        ];
    }
}