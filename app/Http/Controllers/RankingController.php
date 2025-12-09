<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\CampaignsTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\MemoryQuiz;
use App\Models\Puzzle;
use App\Models\AplazoGame;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RankingController extends Controller
{
    use CampaignsTrait;

    public function index()
    {
       
            $campaign = $this->get_current_campaign();
            $campaign_games = $this->has_content_type($campaign->id, 'games');
            $arrData = [
                'campaign'          => $campaign,
                'campaign_games'    => $campaign_games,
                'campaign_tickets'  => [],
                'campaign_coupons'  => [],
            ];
            
            $therIsAnyRankingEnabled = false;

            if(get_app_setting('ranking_enabled_tickets')){
                $therIsAnyRankingEnabled = true;
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
                $arrData ['campaign_tickets'] = $campaign_tickets;
                $arrData ['campaign_coupons'] = $campaign_coupons;
                $arrData ['top_users']        = $top_users->all();
                $arrData ['top_ten_users']    = $top_ten_users->all();
                $arrData ['user_in_top']      = $user_in_top;
                $arrData ['user']             = $user;
            }
             
            if (get_app_setting('ranking_enabled_games')){
                $therIsAnyRankingEnabled = true;
                $arrData ['games_models'] = $this::get_all_models();
            }
            $arrData['thereisAnyRankingEnabled'] = $therIsAnyRankingEnabled;


            return view('dashboard.ranking.index', $arrData);
        
    }

    public function get_ranking_by_model(Request $request){
        $rows_collection = DB::table("user_interactions as ui")->where('ui.model_id', $request->input('modelId'))
            ->join('users as u', 'u.id', '=', 'ui.user_id')
            ->selectRaw('
                ui.model_id as game_id,
                ui.model_title as game_title,
                u.name as user_name,
                u.email,
                u.created_at as user_created_at,
                ui.hit as pivot_hit,
                ui.hit_created_at as hit_created_at,
                ui.hit_updated_at as hit_updated_at,
                ui.code as award_code
            ')
         ->get();
        return response()->json(['message' => 'Data ranking found!', 'data' => $this::calculate_time($rows_collection)]);
            
    }

    private function get_all_models(){
        $arrModels = [];
        foreach(MemoryQuiz::all() as $m){
            $arrModels [$m->id] = $m->title; 
        }
        foreach(Puzzle::all() as $m){
            $arrModels [$m->id] = $m->title; 
        }
        return $arrModels;
    }

    private function calculate_time($rows_collection){
        $arr = [];
        foreach($rows_collection as $k => $row){    
            $arr[$k]['time'] = $this->calculate_ranking_time($row->hit_created_at,$row->hit_updated_at);
            $arr[$k]['user'] = $row->user_name;
            $arr[$k]['email'] = $row->email;
            $arr[$k]['session_email'] = Auth::user()->email;
            if(Auth::user()->email == $row->email){
                $arr[$k]['user'] = 'Tú';
            }
        }  
        
        $times = array_column($arr, 'time');
        array_multisort($times, SORT_ASC, $arr);

        return $arr;
    }

}
