<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePresentacionRequest extends FormRequest
{
    public function rules(): array
    {
        $presentacion = $this->route('presentacion');
        $required = $this->isMethod('PATCH') ? ['sometimes', 'required'] : ['required'];

        return [
            'nombre' => [...$required, 'string', 'max:255', Rule::unique('presentaciones')->ignore($presentacion?->getKey())],
            'descripcion' => ['sometimes', 'nullable', 'string'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }
}
