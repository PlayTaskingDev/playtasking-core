<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'              => ['string', 'max:255'],
            'email'             => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'avatar'            => ['image:jpg,png,jpeg','max:2500'],
            'phone'             => ['nullable', 'digits:10'],
            'members_number'    => [Rule::requiredIf((bool)get_app_setting('members_number')), 'string', 'max:16'],
        ];
    }
}
