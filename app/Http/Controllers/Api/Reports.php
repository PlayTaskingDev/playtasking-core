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
    //El juego menos utilizado por los usuarios
    public function leastUsedGame(){
        $leastUsedGame = UserInteraction::query()
        ->select(
            'model_id',
            'model_title'
        )
        ->selectRaw('COUNT(*) as total_interactions')
        ->groupBy('model_id', 'model_title')
        ->orderBy('total_interactions')
        ->first();

        return response()->json([
            'success' => true,
            'least_used_game' => $leastUsedGame,
        ]);
    }
    // A que hora promedio se registran los usuarios, es decir, el promedio de la hora en que los usuarios se registran en la plataforma
    public function averageRegistrationTime(){
        $averageRegistrationTime = User::query()
        ->selectRaw('
            SEC_TO_TIME(
                AVG(
                    TIME_TO_SEC(TIME(created_at))
                )
            ) as average_registration_time
        ')
        ->value('average_registration_time');
        return response()->json([
            'success' => true,
            'average_registration_time' => $averageRegistrationTime,
        ]);
    }
    //Hora con mayor cantidad de registros
    public function peakRegistrationHour(){
        $peakRegistrationHour = User::query()
        ->selectRaw('HOUR(created_at) as hour')
        ->selectRaw('COUNT(*) as total_registrations')
        ->groupByRaw('HOUR(created_at)')
        ->orderByDesc('total_registrations')
        ->first();
        return response()->json([
            'success' => true,
            'peak_registration_hour' => $peakRegistrationHour,
        ]);
    }
    //Hora con mayor cantidad de interacciones
    public function peakInteractionHour(){
        $peakInteractionHour = UserInteraction::query()
        ->selectRaw('HOUR(created_at) as hour')
        ->selectRaw('COUNT(*) as total_interactions')
        ->groupByRaw('HOUR(created_at)')
        ->orderByDesc('total_interactions')
        ->first();

        return response()->json([
            'success' => true,
            'peak_interaction_hour' => $peakInteractionHour,
        ]);
    }
    //Registros por hora
    public function registrationsByHour(){
        $registrationsByHour = User::query()
        ->selectRaw('HOUR(created_at) as hour')
        ->selectRaw('COUNT(*) as total_registrations')
        ->groupByRaw('HOUR(created_at)')
        ->orderByRaw('HOUR(created_at)')
        ->get();

        return response()->json([
            'success' => true,
            'registrations_by_hour' => $registrationsByHour,
        ]);
    }
    //Interacciones por hora
    public function interactionsByHour(){
        $interactionsByHour = UserInteraction::query()
        ->selectRaw('HOUR(created_at) as hour')
        ->selectRaw('COUNT(*) as total_interactions')
        ->groupByRaw('HOUR(created_at)')
        ->orderByRaw('HOUR(created_at)')
        ->get();

        return response()->json([
            'success' => true,
            'interactions_by_hour' => $interactionsByHour,
        ]);
    }
    //Registros por día
    public function registrationsByDay(){
        $registrationsByDay = User::query()
        ->selectRaw('DATE(created_at) as date')
        ->selectRaw('COUNT(*) as total_registrations')
        ->groupByRaw('DATE(created_at)')
        ->orderByRaw('DATE(created_at)')
        ->get();

        return response()->json([
            'success' => true,
            'registrations_by_day' => $registrationsByDay,
        ]);
    }
    //Interacciones por día
    public function interactionsByDay(){
        $interactionsByDay = UserInteraction::query()
        ->selectRaw('DATE(created_at) as date')
        ->selectRaw('COUNT(*) as total_interactions')
        ->groupByRaw('DATE(created_at)')
        ->orderByRaw('DATE(created_at)')
        ->get();

        return response()->json([
            'success' => true,
            'interactions_by_day' => $interactionsByDay,
        ]);
    }
    //Día de la semana con mayor actividad
    public function mostActiveWeekday(){
        $mostActiveWeekday = UserInteraction::query()
        ->selectRaw('DAYOFWEEK(created_at) as weekday_number')
        ->selectRaw('DAYNAME(created_at) as weekday')
        ->selectRaw('COUNT(*) as total_interactions')
        ->groupByRaw('DAYOFWEEK(created_at), DAYNAME(created_at)')
        ->orderByDesc('total_interactions')
        ->first();

        return response()->json([
            'success' => true,
            'most_active_weekday' => $mostActiveWeekday,
        ]);
    }
    // Nuevos usuarios registrados en el último mes, es decir, los usuarios que se registraron en la plataforma en los últimos 30 días
    public function newUsersMonth(){
        $newUsers = User::query()
        ->whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->count();

        return response()->json([
            'success' => true,
            'new_users_month' => $newUsers,
        ]);
    }
    //Registros por semana
    public function registrationsByWeek(){
        $registrationsByWeek = User::query()
        ->selectRaw('YEAR(created_at) as year')
        ->selectRaw('WEEK(created_at, 1) as week')
        ->selectRaw('COUNT(*) as total_registrations')
        ->groupByRaw('YEAR(created_at), WEEK(created_at, 1)')
        ->orderByRaw('YEAR(created_at)')
        ->orderByRaw('WEEK(created_at, 1)')
        ->get();

        return response()->json([
            'success' => true,
            'registrations_by_week' => $registrationsByWeek,
        ]);
    }
    //Esta métrica sí necesita comparar dos periodos.La forma más natural sería: Mes actual contra mes anterior.
    public function userGrowth(){
        $currentMonthUsers = User::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $previousMonth = now()->copy()->subMonth();

        $previousMonthUsers = User::query()
            ->whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->count();
        
        if ($previousMonthUsers > 0) {
            $growthRate = round(
                (
                    ($currentMonthUsers - $previousMonthUsers)
                    / $previousMonthUsers
                ) * 100,
                2
            );
        } else {
            $growthRate = null;
        }
      return response()->json([
            'success' => true,
            'current_month_users' => $currentMonthUsers,
            'previous_month_users' => $previousMonthUsers,
            'growth_rate' => $growthRate,
        ]);  
        
    }

    public function summary(){
       $interactionSummary = UserInteraction::query()
        ->selectRaw('COUNT(*) as total_interactions')
        ->selectRaw('COUNT(DISTINCT user_id) as participating_users')
        ->selectRaw('COUNT(DISTINCT model_id) as total_used_games')
        ->selectRaw('SUM(CASE WHEN hit = 1 THEN 1 ELSE 0 END) as total_awards')
        ->selectRaw('SUM(CASE WHEN hit = 0 THEN 1 ELSE 0 END) as without_award')
        ->first();
        $totalUsers = User::count();
        $participationRate = $totalUsers > 0
        ? round(
            ($interactionSummary->participating_users / $totalUsers) * 100,
            2
        )
        : 0;
        $averageInteractions = $interactionSummary->participating_users > 0
        ? round(
            $interactionSummary->total_interactions
            / $interactionSummary->participating_users,
            2
        )
        : 0;
        $awardRate = $interactionSummary->total_interactions > 0
        ? round(
            ($interactionSummary->total_awards
            / $interactionSummary->total_interactions) * 100,
            2
        )
        : 0;
        return response()->json([
            'success' => true,

            'summary' => [
                'registered_users' => $totalUsers,

                'participating_users' =>
                    (int) $interactionSummary->participating_users,

                'participation_rate' =>
                    $participationRate . '%',

                'total_interactions' =>
                    (int) $interactionSummary->total_interactions,

                'average_interactions_per_user' =>
                    $averageInteractions,

                'total_used_games' =>
                    (int) $interactionSummary->total_used_games,

                'total_awards_won' =>
                    (int) $interactionSummary->total_awards,

                'participations_without_award' =>
                    (int) $interactionSummary->without_award,

                'award_rate' =>
                    $awardRate . '%',
            ],
        ]);
    }


}