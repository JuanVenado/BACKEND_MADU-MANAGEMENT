<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveCategoriaRequest extends FormRequest
{
    public function rules(): array
    {
        $categoria = $this->route('categoria');
        $required = $this->isMethod('PATCH') ? ['sometimes', 'required'] : ['required'];

        return [
            'nombre' => [...$required, 'string', 'max:255', Rule::unique('categorias')->ignore($categoria?->getKey())],
            'descripcion' => ['sometimes', 'nullable', 'string'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }
}
