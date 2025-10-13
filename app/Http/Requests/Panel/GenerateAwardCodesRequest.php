<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenerateAwardCodesRequest extends FormRequest
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
            'award_id'      => ['required','exists:awards,id'],
            'coupon_type'   => ['required',Rule::in(['unique', 'multiple'])],
            'code'          => [Rule::requiredIf($this->coupon_type == 'multiple'),'string','unique:award_codes,code,except,id'],
            'quantity'      => [Rule::requiredIf($this->coupon_type == 'unique'),'integer'],
            'product'       => ['nullable','string','max:255'],
            'validity'      => ['nullable','string','max:255'],
        ];
    }
}
