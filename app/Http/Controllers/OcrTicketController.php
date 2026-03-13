<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOcrTicketRequest;
use App\Models\AwardCode;
use App\Models\OcrTicket;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Traits\CampaignsTrait;
use App\Traits\UploadImageTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Codesmiths\LaravelOcrSpace\OcrSpaceOptions;
use Codesmiths\LaravelOcrSpace\Facades\OcrSpace;
use Codesmiths\LaravelOcrSpace\Enums\Language;
use Codesmiths\LaravelOcrSpace\Enums\OcrSpaceEngine;
use Illuminate\Support\Facades\DB;

class OcrTicketController extends Controller
{
    use CampaignsTrait, UploadImageTrait;
    
    public function index()
    {
        return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
    }

    public function create(Request $request)
    {
        $campaign = $this->get_current_campaign();
        $campaign_games = $this->has_content_type($campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($campaign->id, 'coupons');

        return view('dashboard.tickets_ocr.create', [
            'campaign'          => $campaign,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }

    public function store(StoreOcrTicketRequest $request)
    {
        $data = $request->validated();

        // OCR
        $binary_image = $data['ticket']->get();
        $mime_type = $data['ticket']->getClientMimeType();
        try {
            $ocr_data = $this->ocr_scan($binary_image,$mime_type);
        } catch (\Throwable $th) {
            return redirect()->route('tickets.ocr.saved', ['tenant' => tenant('id')])->with('status','error');
        }

        $ocr_string = $ocr_data->getParsedResults()->first()->getParsedText();

        // pull substrings
        $date = get_string_coincidence($ocr_string,get_app_setting('ocr_date_string'),get_app_setting('ocr_date_characters'));
        $time = get_string_coincidence($ocr_string,get_app_setting('ocr_time_string'),get_app_setting('ocr_time_characters'));
        $transaction_number = get_string_coincidence($ocr_string,get_app_setting('ocr_transaction_string'),get_app_setting('ocr_transaction_characters'));

        // validate date format
        $campaign = $this->get_current_campaign();
        $date_limit = Carbon::create($campaign->end_date);
        $date_format = get_app_setting('ocr_date_format');
        
        try {
            $ticket_date = Carbon::createFromFormat($date_format, trim($date));
        } catch (\Exception $e) {
            return redirect()->route('tickets.ocr.saved', ['tenant' => tenant('id')])->with('status','date_error');
        }

        if ($ticket_date->greaterThan($date_limit)) {
            return redirect()->route('tickets.ocr.saved', ['tenant' => tenant('id')])->with('status','date_error');
        }

        // Validate if ticket has registered 
        $ticket = OcrTicket::where([
                ['transaction_number',$transaction_number],
            ])->first();

        if($ticket){
            return redirect()->route('tickets.ocr.saved', ['tenant' => tenant('id')])->with('status','duplicated');
        }

        $campaign = $this->get_current_campaign();
        $user = Auth::user();

        // create ticket
        try {
            $ticket_info = $this->uploadImage('gcs','tickets',$request->file('ticket'));
            $ticket = $this->create_ticket($ocr_string,$date,$time,$transaction_number,$ticket_info,$campaign->id,$user->id);
        } catch (\Throwable $th) {
            return redirect()->route('tickets.ocr.saved', ['tenant' => tenant('id')])->with('status','error');
        }

        // validate allowed values
        $settings = Setting::first();
        $allowed_values = $settings->ocr_ticket_phrases;
        $found = false;
        foreach ($allowed_values as $value) {
            if (str_contains(strtolower($ocr_string), strtolower($value))) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            return redirect()->route('tickets.ocr.saved', ['tenant' => tenant('id')])->with('status','error');
        }
        
        $setting = Setting::first();
        $award_code = AwardCode::where([['award_id',$setting->award->id],['active',false]])->first();

        if (!is_null($award_code)) {
            $award_code->active = true;
            $award_code->user_id = Auth::user()->id;
            $award_code->save();

            // Attach user to quiz with hit or not
            DB::table('award_user')->insert([
                'model_id'      => $setting->id,
                'user_id'       => $user->id,
                'model_type'    => \App\Models\Setting::class,
                'hit'           => true,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        } else {
            return redirect()->route('game.out_of_coupons', ['tenant' => tenant('id')]);
        }

        return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $setting->award]))->with('coupon_success',true);
    }

    private function ocr_scan($binary_image,$mime_type)
    {
        $options = OcrSpaceOptions::make()
            ->fileType($mime_type)
            ->language(Language::Spanish)
            ->OCREngine(OcrSpaceEngine::Engine2);;

        $result = OcrSpace::parseBinaryImage(
            $binary_image,
            $options,
        );

        return $result;
    }

    public function saved()
    {
        $campaign = $this->get_current_campaign();

        return view('dashboard.tickets_ocr.saved', [
            'campaign'  => $campaign
        ]);
    }

    private function create_ticket($ocr_string,$date,$time,$transaction_number,$ticket_info,$campaign_id,$user_id)
    {
        $ticket = OcrTicket::create([
            'ocr_string'            => $ocr_string,
            'date'                  => $date,
            'time'                  => $time,
            'transaction_number'    => $transaction_number,
            'img_url'               => $ticket_info,
            'campaign_id'           => $campaign_id,
            'user_id'               => $user_id,
        ]);

        return $ticket;
    }
}
