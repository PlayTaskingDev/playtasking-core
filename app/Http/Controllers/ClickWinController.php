<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Traits\CheckParticipationTrait;
use App\Models\AwardCode;
use App\Models\ClickWin;
use App\Models\UserInteraction;
use Carbon\Carbon;
use App\Traits\CampaignsTrait;

class ClickWinController extends Controller
{
    use CheckParticipationTrait, CampaignsTrait;

    public function index()
    {
        return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
    }

    public function show($slug)
    {
        $today = Carbon::now()->toDateTimeString();
        $click_win = ClickWin::with('campaign', 'award')
            ->where(
            [
                ['slug', $slug],
                ['init_date','<',$today],
                ['end_date','>',$today]
            ])->firstOrFail();

        // Check if user has been participated and won
        $has_paticipated = $this->check_participation($model_id = $click_win->id,$model_type = 'App\Models\ClickWin',$user_id = Auth::user()->id,$hit = true);
        if (!is_null($has_paticipated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $click_win->award]));
        }

        // Give the prize
        DB::table('award_user')->insert([
            'model_id'      => $click_win->id,
            'award_id'     => $click_win->award->id,
            'user_id'       => Auth::user()->id,
            'model_type'    => 'App\Models\ClickWin',
            'hit'           => true,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        $user_interaction = UserInteraction::create([
            'model_id' => $click_win->id,
            'model_title' => $click_win->title,
            'user_id' => Auth::user()->id,
            'hit' => true,
            'hit_created_at' => Carbon::now(),
            'hit_updated_at' => Carbon::now(),
        ]);

        $award_code = AwardCode::where([['award_id',$click_win->award->id],['active',false]])->first();

        if (!is_null($award_code)) {
            $award_code->active = true;
            $award_code->user_id = Auth::user()->id;
            $award_code->save();

            $user_interaction->code = $award_code->code;
            $user_interaction->save();
        } else {
            return redirect()->route('game.out_of_coupons', ['tenant' => tenant('id')]);
        }

        

        return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $click_win->award]));
    }
}
