<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class SaveContentTypeRequest extends FormRequest
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
            'name'              => ['required','string'],
            'description'       => ['required','string'],
            'icon'              => ['image:jpg,png,jpeg,svg','max:2024'],
            'icon_active'       => ['image:jpg,png,jpeg,svg','max:2024'],
            'section_banner'    => ['image:jpg,png,jpeg,svg','max:2024'],
            'gradient_1'        => ['required','string'],
            'gradient_2'        => ['required','string'],
            'delete_image_holder_hidden'    => ['nullable','boolean'],
            'game_banner_url' => ['nullable','string','url'],
            'game_banner_video' => ['nullable','string','url'],
        ];
    }
}
