<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'unidad' => 'required|exists:unidades,id',
            'categoria' => 'required|exists:categorias,id',
            'activo' => 'boolean',
            'existencial' => 'boolean',
            'stock_min' => 'integer|min:0',
            'coding' => 'required|string|unique:productos,coding,' . $this->producto?->id,
        ];
    }
}