<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\CampaignsTrait;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    use CampaignsTrait;

    public function index()
    {
        $campaign = $this->get_current_campaign();
        $campaign_games = $this->has_content_type($campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($campaign->id, 'coupons');

        if (is_null($campaign_tickets) && is_null($campaign_coupons)) {
            return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
        }

        $user = Auth::user();
        $top_ranking = User::where('points','>',0)->orderBy('ranking', 'asc')->limit(10)->get();
        $top_users = $top_ranking->map(function ($item, int $key) use ($user) {
            if ($item->id == $user->id) {
                $item->is_user = true;
            } else {
                $item->is_user = false;
            }
            return $item;
        });
        $user_in_top = $top_ranking->contains(function ($item, int $key) use ($user) {
            return $item->id == $user->id;
        });
        $top_ten_users = $top_users->splice(3);

        return view('dashboard.ranking.index', [
            'campaign'          => $campaign,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
            'top_users'         => $top_users->all(),
            'top_ten_users'     => $top_ten_users->all(),
            'user_in_top'       => $user_in_top,
            'user'              => $user,
        ]);
    }
}
