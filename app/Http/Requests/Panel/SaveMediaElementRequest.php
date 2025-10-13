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
            'description'   => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñⓇ$,.;:!"¡?¿#\(\)\' \-]+$/'],
            'asset'         => [Rule::requiredIf(!$this->id),'image:jpg,png,jpeg,svg','max:2024'],
        ];
    }
}
