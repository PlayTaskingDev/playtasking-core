<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Traits\CampaignsTrait;
use Carbon\Carbon;

class StoreTicketRequest extends FormRequest
{

    use CampaignsTrait;
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
        $campaign = $this->get_current_campaign();
        $today = Carbon::now();
        ini_set('upload_max_filesize', '12M');
        ini_set('post_max_size', '12M');

        return [
            'transaction_number'    => ['required','regex:/^[A-Z0-9]+$/'],
            'transaction_date'      => ['required','date_format:Y-m-d','after_or_equal:' . $campaign->init_date, 'before_or_equal:' . $today->toDateString()],
            'store'                 => ['required','regex:/^[A-Z0-9]+$/'],
            'amount'                => ['required','numeric','min:260'],
            //'ticket'                => [Rule::filepond(['required','image','max:12000'])],
            'ticket'                => ['required','file','max:12000',],
            'ticket_answer'         => [Rule::requiredIf(fn () => get_app_setting('tickets_quiz_validation')),'exists:ticket_answers,id'],
            'quid'                  => [Rule::requiredIf(fn () => get_app_setting('tickets_quiz_validation')),'exists:ticket_questions,id'],
        ];
    }
}
