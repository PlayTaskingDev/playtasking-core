<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class GenerateRandomAwardCodesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:20000',
            ],

            'product' => [
                'nullable',
                'string',
                'max:255',
            ],

            'validity' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' =>
                'Indica la cantidad de códigos a generar.',

            'quantity.min' =>
                'Debes generar al menos un código.',

            'quantity.max' =>
                'Puedes generar como máximo 20,000 códigos por operación.',
        ];
    }
}