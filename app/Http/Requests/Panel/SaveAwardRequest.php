<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class SaveAwardRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
            ],

            'content' => [
                'required',
            ],

            'awardable_id' => [
                'required',
                'uuid',
            ],

            'awardable_type' => [
                'required',
                'string',
            ],
        ];
    }
}