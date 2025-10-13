<?php

namespace App\Http\Requests\Panel;

use App\Rules\NoOverlapDate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveCampaignRequest extends FormRequest
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
            'name'                  => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ,.;:!"¡?¿#\(\)\' \-]+$/'],
            'description'           => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ,.;:!"¡?¿#\(\)\' \-]+$/'],
            'slug'                  => [
                'required',
                'regex:/^[a-z0-9\-]+$/',
                Rule::unique('campaigns')->ignore($this->id)
            ],
            'init_date'             => ['required','date_format:Y-m-d H:i:s',new NoOverlapDate],
            'end_date'              => ['required','date_format:Y-m-d H:i:s',new NoOverlapDate],
            'active'                => ['boolean'],
            'games'                 => ['exists:content_types,id'],
            'tickets'               => ['exists:content_types,id'],
            'coupons'               => ['exists:content_types,id'],
        ];
    }
}
