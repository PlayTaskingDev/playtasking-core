<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VoteContestAssetRequest extends FormRequest
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
            'id'                    => ['required','exists:vote_contest_assets,id'],
            'email'                 => ['required','email', Rule::unique('vote_contest_votations')->where(fn ($query) => $query->where('vote_contest_asset_id', $this->id))],
            'g-recaptcha-response'  => 'required|recaptchav3:vote,0.5'
        ];
    }
}
