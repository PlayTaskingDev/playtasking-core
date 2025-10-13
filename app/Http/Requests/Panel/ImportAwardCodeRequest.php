<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class ImportAwardCodeRequest extends FormRequest
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
            'file'      => ['required','mimes:xls,xlsx,csv'],
            'product'   => ['nullable','string','max:255'],
            'validity'  => ['nullable','string','max:255'],
            'award_id'  => ['required','exists:awards,id']
        ];
    }
}
