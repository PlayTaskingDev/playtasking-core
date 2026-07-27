<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AwardCode;
use App\Models\Award;
use App\Models\Campaign;
use App\Traits\CampaignsTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Reports extends Controller
{
    use CampaignsTrait;


    
    public function getUsers(){
        $users = User::paginate(100,['id', 'name', 'email','phone','created_at as registered_at']);

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }

    public function getActiveCampaign(){
        $now = Carbon::now()->toDateTimeString();
        $campaign = Campaign::where([['active',true],['init_date','<',$now],['end_date','>',$now]])->first();
        $campaign->makeHidden(['init_date', 'end_date', 'description', 'created_at', 'updated_at','only_date']);
        return response()->json([
            'success' => true,
            'campaign' => $campaign,
        ]);
    }

    public function getActiveGames($slug){
        $now = Carbon::now()->toDateTimeString();
        $gameRelations = [
            'share_quizzes',
            'quizzes',
            'memory_quizzes',
            'vote_contests',
            'click_wins',
            'aplazo_games',
            'puzzles',
            'catch_games',
            'smash_games',
            'flappy_games',
            'penal_games',
        ];

        $relations = collect($gameRelations)
            ->mapWithKeys(function ($relation) use ($now) {
                return [
                    $relation => function ($query) use ($now) {
                        $query
                            ->where('init_date', '<', $now)
                            ->where('end_date', '>', $now)
                            ->select(['id', 'campaign_id', 'title', 'init_date', 'end_date'])
                            ->withOut('only_date');
                    },
                ];
            })
            ->all();

        $campaign = Campaign::query()
            ->select(['id', 'slug','name', 'init_date', 'end_date'])
            ->with($relations)
            ->where('slug', $slug)
            ->where('active', true)
            ->where('init_date', '<', $now)
            ->where('end_date', '>', $now)
            ->first();

        if ($campaign) {
            foreach ($gameRelations as $relation) {
                $campaign->{$relation}->each(function ($game) {
                    $game->makeHidden([
                        'campaign_id',
                        'table_name',
                        'is_valid',
                        'model_name',
                        'only_date',
                        'award',
                    ]);
                });
            }
            $campaign->makeHidden(['init_date', 'end_date', 'description', 'created_at', 'updated_at','only_date']);
        }
     return response()->json([  
            'success' => true, 
            'games' => $campaign,
        ]);
    }

    public function getInteractionsByGame($game_id){
        $rows_collection = $this->get_user_interactions($game_id);
        foreach ($rows_collection as $row) {
            $hitCreatedAt = $row->hit_created_at; 
            $hitUpdatedAt = $row->hit_updated_at; 

            $createdAt = Carbon::createFromFormat(
                'Y-m-d H:i:s.u',
                $hitCreatedAt
            );

            $updatedAt = Carbon::createFromFormat(
                'Y-m-d H:i:s.u',
                $hitUpdatedAt
            );

            $row->diff_microseconds = $createdAt->diffInMicroseconds($updatedAt, false);
        }
        return response()->json([
            'interactions' => $rows_collection,
        ]);
    }

    public function getInteractionsByUser($user_email){
        $rows_collection = DB::table("user_interactions as ui")->where('u.email', $user_email)
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
        
        foreach ($rows_collection as $row) {
            $hitCreatedAt = $row->hit_created_at; 
            $hitUpdatedAt = $row->hit_updated_at; 

            $createdAt = Carbon::createFromFormat(
                'Y-m-d H:i:s.u',
                $hitCreatedAt
            );

            $updatedAt = Carbon::createFromFormat(
                'Y-m-d H:i:s.u',
                $hitUpdatedAt
            );

            $row->diff_microseconds = $createdAt->diffInMicroseconds($updatedAt, false);
        }
        return response()->json([
            'success' => true,
            'interactions' => $rows_collection,
        ]);
    }

    public function getTotalCoupons(){
        $totalCoupons = AwardCode::count();
        return response()->json([
            'success' => true,
            'total_coupons' => $totalCoupons,
        ]);
    }

    public function getAwardByCode($code){
        $award_code = AwardCode::where('code', $code)->first();
        if ($award_code) {
            $award = Award::find($award_code->award_id);
            return response()->json([
                'success' => true,
                'award' => $award,
                'award_code' => $award_code,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Award code not found',
            ], 404);
        }
    }

    
}
