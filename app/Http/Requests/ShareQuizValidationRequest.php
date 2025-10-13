<?php

namespace App\Http\Requests;

use App\Rules\SocialDomains;
use Illuminate\Foundation\Http\FormRequest;

class ShareQuizValidationRequest extends FormRequest
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
            'share_quiz'    => ['required','exists:share_quizzes,id'],
            'post_url'      => ['required','url',new SocialDomains]
        ];
    }
}
