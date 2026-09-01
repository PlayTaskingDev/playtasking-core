<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveMediaElementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'asset' => [
                'required',
                'array',
            ],

            'asset.*' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,svg',
                'max:4096',
            ],
        ];
    }
}
