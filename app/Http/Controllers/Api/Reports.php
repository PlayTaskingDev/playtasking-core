<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInteraction;
use App\Models\AwardCode;
use App\Models\Award;
use App\Models\Campaign;
use App\Traits\CampaignsTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Reports extends Controller
{
    use CampaignsTrait;


    //Total de usuarios registrados en la plataforma
    public function getCountUsers(){
        $count = User::count();

        return response()->json([
            'success' => true,
            'users_count' => $count,
        ]);
    }
    //Total de usuarios que han participado en al menos un juego
    public function getTotalParticipatingUsers(){
        $totalParticipants = UserInteraction::distinct('user_id')->count('user_id');

        return response()->json([
            'success' => true,
            'total_participating_users' => $totalParticipants,
        ]);
    }
    //Conversión a porcentaje de los usuarios que participaron en al menos un juego
    /*
    Por ejemplo, si tienes:
    Usuarios registrados: 1,000
    Usuarios que jugaron:   650
    Entonces la conversión sería:
    650 / 1,000 × 100 = 65%
     */
    public function conversionRegisterToParticipate(){
        $totalUsers = User::count();
        $totalParticipants = UserInteraction::distinct('user_id')
            ->count('user_id');
        $conversionRate = $totalUsers > 0
            ? round(($totalParticipants / $totalUsers) * 100, 2)
            : 0;

        return response()->json([
            'success' => true,
            'conversion_rate' => $conversionRate . '%',
        ]);
    }
    //Total de interacciones realizadas por todos los usuarios
    public function totalInteractions(){
        $totalInteractions = UserInteraction::count();

        return response()->json([
            'success' => true,
            'total_interactions' => $totalInteractions,
        ]);
    }

    //Cada usuario que participo realizo en promedio 'X' interacciones
    public function totalInteractionsAverage(){
        $totalInteractions = UserInteraction::count();
        $totalParticipants = UserInteraction::distinct('user_id')
            ->count('user_id');
        $averageInteractionsPerUser = $totalParticipants > 0
            ? round($totalInteractions / $totalParticipants, 2)
            : 0;
        return response()->json([
            'success' => true,
            'average_interactions' => $averageInteractionsPerUser,
        ]);
    }

    //Total de juegos utilizados por los usuarios
    public function totalUsedGames(){
        $totalGamesUsed = UserInteraction::distinct('model_id')->count('model_id');

        return response()->json([
            'success' => true,
            'total_used_games' => $totalGamesUsed,
        ]);
    }
    //El juego más utilizado por los usuarios
    public function totalMostUsedGames(){
        $mostUsedGame = UserInteraction::select(
            'model_id',
            'model_title',
            DB::raw('COUNT(*) as total_interactions')
        )
        ->groupBy('model_id', 'model_title')
        ->orderByDesc('total_interactions')
        ->first();

        return response()->json([
            'success' => true,
            'most_used_game' => $mostUsedGame,
        ]);
    }
    //Total de interacciones por juego
    public function interactionsByGame(){
        $interactionsByGame = UserInteraction::select(
            'model_id',
            'model_title',
            DB::raw('COUNT(*) as total_interactions')
        )
        ->groupBy('model_id', 'model_title')
        ->orderByDesc('total_interactions')
        ->get();

        return response()->json([
            'success' => true,
            'interactions_by_game' => $interactionsByGame,
        ]);
    }

    //Total de usuarios únicos por juego, siempre deberá ser 1 en total_users, ya que un usuario no puede interactuar más de una vez con un mismo juego
    public function uniqueUsersByGame()
    {
        $uniqueUsersByGame = UserInteraction::select(
            'model_id',
            'model_title',
            DB::raw('COUNT(DISTINCT user_id) as total_users')
        )
        ->groupBy('model_id', 'model_title')
        ->orderByDesc('total_users')
        ->get();

        return response()->json([
            'success' => true,
            'unique_users_by_game' => $uniqueUsersByGame,
        ]);
    }

    //total de premios ganados por los usuarios
    public function totalAwardsWon(){
        $totalAwards = UserInteraction::where('hit', true)->count();
        return response()->json([
            'success' => true,
            'total_awards_won' => $totalAwards,
        ]);
    }

    //total de premios no ganados por los usuarios, es decir, aquellos que participaron pero no obtuvieron ningún premio
    public function totalParticipantsWithoutAward(){
       $totalWithoutAward = UserInteraction::where('hit', 0)->count();
        return response()->json([
            'success' => true,
            'total_participants_without_award' => $totalWithoutAward,
        ]);
    }

    //total en porcentaje de premios ganados por los usuarios, es decir, aquellos que participaron y ganaron premio
    public function conversionParticipantsWithAward(){
        $totalInteractions = UserInteraction::count();
        $totalAwards = UserInteraction::where('hit', 1)->count();
        $awardRate = $totalInteractions > 0
            ? round(($totalAwards / $totalInteractions) * 100, 2)
            : 0;
        return response()->json([
            'success' => true,
            'conversion_rate' => $awardRate . '%',
        ]);
    }

    //Tiempo promedio de resolución por juego, es decir, el tiempo promedio que tarda un usuario en completar un juego desde que inicia hasta que termina
    public function averageResolutionTimePerGame(){
        $averageTimeByGame = UserInteraction::select(
            'model_id',
            'model_title'
        )
        ->selectRaw('
            ROUND(
                AVG(
                    TIMESTAMPDIFF(
                        MICROSECOND,
                        hit_created_at,
                        hit_updated_at
                    ) / 1000000
                ),
                2
            ) as average_seconds
        ')
        ->whereNotNull('hit_created_at')
        ->whereNotNull('hit_updated_at')
        ->whereColumn('hit_updated_at', '>', 'hit_created_at')
        ->groupBy('model_id', 'model_title')
        ->get();

        return response()->json([
            'success' => true,
            'average_resolution_time_per_game' => $averageTimeByGame,
        ]);
    }

    //Tiempo minimo de resolución por juego, es decir, el tiempo minimo que tarda un usuario en completar un juego desde que inicia hasta que termina
    public function minimumResolutionTimePerGame(){
        $minimumTimeByGame = UserInteraction::select(
            'model_id',
            'model_title'
        )
        ->selectRaw('
            ROUND(
                MIN(
                    TIMESTAMPDIFF(
                        MICROSECOND,
                        hit_created_at,
                        hit_updated_at
                    ) / 1000000
                ),
                2
            ) as minimum_seconds
        ')
        ->whereNotNull('hit_created_at')
        ->whereNotNull('hit_updated_at')
        ->whereColumn('hit_updated_at', '>', 'hit_created_at')
        ->groupBy('model_id', 'model_title')
        ->get();

        return response()->json([
            'success' => true,
            'minimum_resolution_time_per_game' => $minimumTimeByGame,
        ]);
    }

    //Tiempo maximo de resolución por juego, es decir, el tiempo maximo que tarda un usuario en completar un juego desde que inicia hasta que termina
    public function maximumResolutionTimePerGame(){
        $maximumTimeByGame = UserInteraction::select(
            'model_id',
            'model_title'
        )
        ->selectRaw('
            ROUND(
                MAX(
                    TIMESTAMPDIFF(
                        MICROSECOND,
                        hit_created_at,
                        hit_updated_at
                    ) / 1000000
                ),
                2
            ) as maximum_seconds
        ')
        ->whereNotNull('hit_created_at')
        ->whereNotNull('hit_updated_at')
        ->whereColumn('hit_updated_at', '>', 'hit_created_at')
        ->groupBy('model_id', 'model_title')
        ->get();

        return response()->json([
            'success' => true,
            'maximum_resolution_time_per_game' => $maximumTimeByGame,
        ]);
    }
    
}
