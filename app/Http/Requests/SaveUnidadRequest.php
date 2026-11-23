<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUnidadRequest extends FormRequest
{
    public function rules(): array
    {
        $unidad = $this->route('unidad');
        $required = $this->isMethod('PATCH') ? ['sometimes', 'required'] : ['required'];

        return [
            'nombre' => [...$required, 'string', 'max:255', Rule::unique('unidades')->ignore($unidad?->getKey())],
            'abreviatura' => [...$required, 'string', 'max:10', Rule::unique('unidades')->ignore($unidad?->getKey())],
            'activo' => ['sometimes', 'boolean'],
        ];
    }
}
