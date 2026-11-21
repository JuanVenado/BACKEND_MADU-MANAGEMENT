<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreMovimientoRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'folio' => 'nullable|string|unique:movimientos,folio',
            'cantidad' => 'required|numeric|min:0.01',
            'producto' => 'required|exists:productos,id',
            'producto_presentacion' => 'required|exists:producto_presentacion,id',
            'tipo' => 'required|in:entrada,salida,ajuste',
            'motivo' => 'required_if:tipo,ajuste|nullable|string|max:255',
        ];
    }
}