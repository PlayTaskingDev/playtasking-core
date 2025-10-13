<?php

namespace App\Http\Requests\Panel;

use App\Enums\CodeTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class SaveCodeRequest extends FormRequest
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
            'campaign_id'       => ['required','exists:campaigns,id'],
            'content_type_id'   => ['required','exists:content_types,id'],
            'title'             => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ,.;:!"¡?¿#\(\)\' \-]+$/'],
            'points'            => ['required','integer'],
            'featured_image'    => [Rule::requiredIf(!$this->id),'image:jpg,png,jpeg','max:600'],
            'type'              => ['required',new Enum(CodeTypeEnum::class)],
            'init_date'         => ['required','date_format:Y-m-d H:i:s'],
            'end_date'          => ['required','date_format:Y-m-d H:i:s'],
            'active'            => ['boolean'],
            'gradient_1'        => ['required','string'],
            'gradient_2'        => ['required','string'],
            'btn_background_color_1'    => ['nullable','string'],
            'btn_background_color_2'    => ['nullable','string'],
            'btn_border_color'          => ['nullable','string'],
            'btn_border'                => ['nullable','boolean'],
            'btn_text_active'           => ['nullable','string'],
            'btn_text_inactive'         => ['nullable','string'],
            'btn_shadow'                => ['nullable','boolean'],
            'btn_text_color'            => ['nullable','string'],
        ];
    }
}
