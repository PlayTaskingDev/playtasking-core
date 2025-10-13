<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class SaveCampaignSplashPageRequest extends FormRequest
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
            'instructions'                  => ['required','string'],
            'featured_image_url'            => ['image:jpg,png,jpeg','max:600'],
            'featured_video_url'            => ['nullable','url'],
            'campaign_id'                   => ['required','exists:campaigns,id'],
            'delete_image_holder_hidden'    => ['nullable','boolean']
        ];
    }
}
