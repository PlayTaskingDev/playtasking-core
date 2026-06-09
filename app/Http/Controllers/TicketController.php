<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use App\Models\TicketQuestion;
use Illuminate\Http\Request;
use App\Traits\CampaignsTrait;
use App\Traits\UploadImageTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use RahulHaque\Filepond\Facades\Filepond;
use Illuminate\Support\Str;

class TicketController extends Controller
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
        $today = Carbon::now();
        $init_date = Carbon::create($campaign->init_date);

        if (get_app_setting('tickets_quiz_validation')) {
            $ticket_question = TicketQuestion::with(['ticket_answers' => function($q){
                $q->inRandomOrder();
            }])->inRandomOrder()->limit(1)->first();
        }
        
        return view('dashboard.tickets.create', [
            'campaign'          => $campaign,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
            'ticket_question'   => $ticket_question ?? null,
            'today'             => $today->toDateString(),
            'init_date'         => $init_date->toDateString(),
        ]);
    }

    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();

        // Validate if ticket has registered 
        $ticket = Ticket::where([
                ['transaction_number',$data['transaction_number']],
                //['transaction_date',$data['transaction_date']],
                //['store',$data['store']],
                //['transaction_amount',$data['amount']],
            ])->first();

        if($ticket){
            return redirect()->route('tickets.saved', ['tenant' => tenant('id')])->with('status','duplicated');
        }
        // OCR
        $binary_image = $data['ticket']->get();
        $mime_type = $data['ticket']->getClientMimeType();
        try {
            $ocr_data = $this->ocr_scan($binary_image,$mime_type);
        } catch (\Throwable $th) {
            return redirect()->route('tickets.ocr.saved', ['tenant' => tenant('id')])->with('status','error');
        }
        dd($ocr_data);

        $campaign = $this->get_current_campaign();
        $user = Auth::user();
        $points = get_app_setting('tickets_points');

        try {
           /* $request_file_name = Str::uuid();
           $ticket_info = Filepond::field($data['ticket'])->moveTo(tenant('id') . '/tickets/' . $request_file_name); */

           $ticket_info = $this->uploadImage('gcs','tickets',$request->file('ticket'));
           $ticket = $this->create_ticket($data,$ticket_info,$points,$campaign->id,$user->id);
       } catch (\Throwable $th) {
            return redirect()->route('tickets.saved', ['tenant' => tenant('id')]);
       }
        
        // Validate response
        if (get_app_setting('tickets_quiz_validation')) {
            $ticket_question = TicketQuestion::whereId($data['quid'])->with([
                'ticket_answers' => function($q){
                    $q->where('is_correct',true);
                }
            ])->first();

            if ($ticket_question->ticket_answers->first()->id != $data['ticket_answer']) {
                $ticket->points = 0;
                $ticket->save();
                return redirect()->route('tickets.saved', ['tenant' => tenant('id')])->with('status','error');
            } else {
                $ticket->guessed = true;
                $ticket->save();
            }
        }

        $user->points = $user->points + $points;
        $user->save();

        return redirect()->route('tickets.saved', ['tenant' => tenant('id')])->with('status','success');
    }

    public function saved()
    {
        $campaign = $this->get_current_campaign();

        return view('dashboard.tickets.saved', [
            'campaign'  => $campaign
        ]);
    }

    private function create_ticket($data,$ticket_info,$points,$campaign_id,$user_id)
    {
        $ticket = Ticket::create([
            'transaction_number'    => $data['transaction_number'],
            'transaction_date'      => $data['transaction_date'],
            'store'                 => $data['store'],
            'transaction_amount'    => $data['amount'],
            //'img_url'               => $ticket_info['url'],
            'img_url'               => $ticket_info,
            'points'                => $points,
            'campaign_id'           => $campaign_id,
            'user_id'               => $user_id,
        ]);

        return $ticket;
    }
}
