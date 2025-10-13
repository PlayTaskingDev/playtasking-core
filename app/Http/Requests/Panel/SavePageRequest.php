<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePageRequest extends FormRequest
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
            'title'         => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ,.;:!"¡?¿\(\)\' \-]+$/'],
            'description'   => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ,.;:!"¡?¿\(\)\' \-]+$/'],
            'slug'          => [
                'required',
                'regex:/^[a-z0-9\-]+$/',
                Rule::unique('pages')->ignore($this->id)
            ],
            'icon'          => ['image:jpg,png,jpeg','max:2000'],
            'content'       => ['required','string'],
            'active'        => ['boolean']
        ];
    }
}
