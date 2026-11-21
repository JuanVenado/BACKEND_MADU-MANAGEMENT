<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovimientoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'folio' => 'nullable|string|max:255|unique:movimientos,folio',
            'cantidad' => 'required|numeric|decimal:0,2|between:-99999999.99,99999999.99',
            'producto' => 'required|integer|exists:productos,id',
            'producto_presentacion' => 'required|integer|exists:producto_presentacion,id',
            'tipo' => 'required|in:entrada,salida,ajuste',
            'motivo' => 'required_if:tipo,ajuste|nullable|string|max:255',
            'referencia' => 'nullable|string|max:255',
        ];
    }
}
