<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveProveedorRequest extends FormRequest
{
    public function rules(): array
    {
        $proveedor = $this->route('proveedor');
        $required = $this->isMethod('PATCH') ? ['sometimes', 'required'] : ['required'];

        return [
            'nombre' => [...$required, 'string', 'max:255', Rule::unique('proveedores')->ignore($proveedor?->getKey())],
            'rfc' => [
                'bail',
                ...$required,
                'string',
                'regex:/\A[A-ZÑ&]{3,4}[0-9]{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12][0-9]|3[01])[A-Z0-9]{3}\z/u',
                Rule::unique('proveedores')->ignore($proveedor?->getKey()),
            ],
            'telefono' => ['sometimes', 'nullable', 'string', 'max:255'],
            'mail' => ['sometimes', 'nullable', 'email', 'max:255'],
            'direccion' => ['sometimes', 'nullable', 'string', 'max:255'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'rfc.regex' => 'El RFC debe tener el formato de persona física (13 caracteres) o moral (12 caracteres).',
        ];
    }
}
