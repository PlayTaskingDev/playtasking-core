<?php

namespace App\Http\Requests;

use App\Models\VoteContest;
use Illuminate\Foundation\Http\FormRequest;

class StoreVoteContest extends FormRequest
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

    public function attributes(): array
{
    return [
        'title' => 'Descripción',
    ];
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $vote_contest = VoteContest::findOrFail($this->vote_contest);
        $mimes = $vote_contest->asset_type == 'photo' ? 'png,jpg,jpeg,bmp,heic,tiff' : 'mp4,mpeg,avi,mov';
        ini_set('upload_max_filesize', $vote_contest->asset_kb_size);
        ini_set('post_max_size', $vote_contest->asset_kb_size);

        return [
            'vote_contest'  => ['required','exists:vote_contests,id'],
            'title'         => ['required','max:600','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ,.;:!"¡?¿\(\)\' \-]+$/'],
            'asset'         => ['required','max:' . $vote_contest->asset_kb_size,'mimes:' . $mimes]
        ];
    }
}
