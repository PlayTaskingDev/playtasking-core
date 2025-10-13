<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class SaveMemoryCardRequest extends FormRequest
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
            'name'              => ['required','regex:/^[a-z0-9\-]+$/'],
            'memory_quiz_id'    => ['required','exists:memory_quizzes,id'],
            'featured_image'    => ['image:jpg,png,jpeg','max:600'],
        ];
    }
}
