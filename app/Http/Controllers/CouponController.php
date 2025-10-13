<?php

namespace App\Http\Controllers;

use App\Exports\AwardCodeExport;
use App\Http\Requests\ValidateCouponRequest;
use App\Models\AwardCode;
use App\Models\Code;
use App\Models\UserInteraction;
use Illuminate\Http\Request;
use App\Traits\CampaignsTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\CheckParticipationTrait;

class CouponController extends Controller
{
    use CampaignsTrait, CheckParticipationTrait;
    
    public function index()
    {
        return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
    }

    public function capture()
    {
        $campaign = $this->get_current_campaign();
        $campaign_games = $this->has_content_type($campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($campaign->id, 'coupons');

        return view('dashboard.coupons.create', [
            'campaign'          => $campaign,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
            'ticket_question'   => $ticket_question ?? null,
        ]);
    }

    public function validation(ValidateCouponRequest $request)
    {
        $data = $request->validated();

        $campaign = $this->get_current_campaign();
        $now = Carbon::now()->toDateTimeString();
        $code = Code::with('award')->where([['campaign_id',$campaign->id],['active',true],['init_date','<',$now],['end_date','>',$now]])->first();

        if (!$code) {
            return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
        }

        // If coupon type is multiple, the user can only participate once
        if ($code->type == 'multiple') {
            // Check if user has been participated and won
            $has_paticipated = $this->check_participation($model_id = $code->id,$model_type = 'App\Models\Code',$user_id = Auth::user()->id,$hit = true);
            if (!is_null($has_paticipated)) {
                return redirect()->route('coupons.duplicated', ['tenant' => tenant('id')]);
            }
        }

        $award_code = AwardCode::where([['active',false],['code',$data['coupon_code']]])->first();
        // The code inserted does not exist or has been taken by other user
        if (!$award_code) {
            return redirect()->route('coupons.incorrect', ['tenant' => tenant('id')]);
        }

        if ($code->type == 'unique' || $code->type == 'unique_external') {
            // assign code to user
            $award_code->active = true;
            $award_code->user_id = Auth::user()->id;
            $award_code->save();
        }

        // insert participation
        DB::table('award_user')->insert([
            'model_id'      => $code->id,
            'award_id'      => $code->award->id,
            'user_id'       => Auth::user()->id,
            'model_type'    => 'App\Models\Code',
            'hit'           => true,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        UserInteraction::create([
            'model_id' => $code->id,
            'model_title' => $code->title,
            'user_id' => Auth::user()->id,
            'hit' => true,
            'hit_created_at' => Carbon::now()->format('Y-m-d H:i:s.u'),
            'hit_updated_at' => Carbon::now()->format('Y-m-d H:i:s.u'),
            'code' => $award_code->code,
        ]);

        $user = Auth::user();
        $user->points = $user->points + $code->points;
        $user->save();

        if ($code->type == 'multiple') {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $code->award]))->with('coupon_success',true);
        } else {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $code->award]));
        }
    }

    public function incorrect()
    {
        $campaign = $this->get_current_campaign();
        $campaign_games = $this->has_content_type($campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($campaign->id, 'coupons');

        return view('dashboard.coupons.incorrect', [
            'campaign'          => $campaign,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }

    public function duplicated()
    {
        $campaign = $this->get_current_campaign();
        $campaign_games = $this->has_content_type($campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($campaign->id, 'coupons');

        return view('dashboard.coupons.incorrect', [
            'campaign'          => $campaign,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }
}
