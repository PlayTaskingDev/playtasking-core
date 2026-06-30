<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class SaveAnswerRequest extends FormRequest
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
            'title'         => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ,.;:®!"¡?¿#\(\)\' \-]+$/'],
            'question_id'   => ['required','exists:questions,id'],
            'is_correct'    => ['boolean'],
            'featured_image'=> ['image:jpg,png,jpeg','max:600','dimensions:width=500,height=300'],
        ];
    }
}
