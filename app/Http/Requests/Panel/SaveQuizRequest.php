<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveQuizRequest extends FormRequest
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
            'description'       => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ,.;:!"¡?¿#\(\)\' \-]+$/'],
            'featured_image'    => [Rule::requiredIf(!$this->id),'image:jpg,png,jpeg','max:600'],
            'featured_image_disabled'   => [Rule::requiredIf(!$this->id),'image:jpg,png,jpeg','max:600'],
            'slug'              => [
                'required',
                'regex:/^[a-z0-9\-]+$/',
                Rule::unique('quizzes')->ignore($this->id)
            ],
            'init_date'         => ['required','date_format:Y-m-d H:i:s'],
            'end_date'          => ['required','date_format:Y-m-d H:i:s'],
            'gradient_1'        => ['required','string'],
            'gradient_2'        => ['required','string'],
            'failed_response'   => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ,.;:!"¡?¿#\(\)\' \-]+$/'],
            'failed_image'      => [Rule::requiredIf(!$this->id),'image:jpg,png,jpeg','max:600'],
            'delete_image_holder_hidden'  => ['nullable','boolean'],
            'game_banner'       => ['nullable','image:jpg,png,jpeg','max:600'],
            'game_banner_url'   => ['nullable','url'],
            'game_banner_video' => ['nullable','url'],
            'btn_background_color_1' => ['nullable','string'],
            'btn_background_color_2' => ['nullable','string'],
            'btn_border_color'  => ['nullable','string'],
            'btn_border'        => ['nullable','boolean'],
            'btn_text_active'   => ['nullable','string'],
            'btn_text_inactive' => ['nullable','string'],
            'btn_shadow'        => ['nullable','boolean'],
            'btn_text_color'    => ['nullable','string'],
        ];
    }
}
