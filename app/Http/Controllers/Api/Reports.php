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
use Illuminate\Database\Eloquent\Builder;

class Reports extends Controller
{
    use CampaignsTrait;


    private function getDateRange(Request $request): array
    {
        $request->validate([
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:date_from'
            ],
        ]);

        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : null;

        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : null;

        return [$dateFrom, $dateTo];
    }
    private function applyDateRange(
        $query,
        ?Carbon $dateFrom,
        ?Carbon $dateTo,
        string $column = 'created_at'
    ) {
        return $query
            ->when(
                $dateFrom,
                fn ($query) =>
                    $query->where($column, '>=', $dateFrom)
            )
            ->when(
                $dateTo,
                fn ($query) =>
                    $query->where($column, '<=', $dateTo)
            );
    }

    //1.- Total de usuarios registrados en la plataforma
    public function getCountUsers(Request $request){
        [$dateFrom, $dateTo] = $this->getDateRange($request);
        $query = User::query();
        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );
        return response()->json([
            'success' => true,
            'users_count' => $query->count(),
        ]);
    }
    //2.- Total de usuarios que han participado en al menos un juego
    public function getTotalParticipatingUsers(Request $request){
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $total = $query
            ->distinct('user_id')
            ->count('user_id');

        return response()->json([
            'success' => true,
            'total_participating_users' => $total,
        ]);
    }
    //3.- Conversión a porcentaje de los usuarios que participaron en al menos un juego
    /*
    Por ejemplo, si tienes:
    Usuarios registrados: 1,000
    Usuarios que jugaron:   650
    Entonces la conversión sería:
    650 / 1,000 × 100 = 65%
     */
    public function conversionRegisterToParticipate(Request $request){
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        /*
        * Sin rango conservamos la lógica histórica.
        */
        if (!$dateFrom && !$dateTo) {

            $registeredUsers = User::count();

            $participatingUsers = UserInteraction::query()
                ->distinct('user_id')
                ->count('user_id');

            $rate = $registeredUsers > 0
                ? round(
                    ($participatingUsers / $registeredUsers) * 100,
                    2
                )
                : 0;

            return response()->json([
                'success' => true,
                'conversion_rate' => $rate . '%',
            ]);
        }

        /*
        * Usuarios registrados dentro del periodo.
        */
        $registeredQuery = User::query();

        $this->applyDateRange(
            $registeredQuery,
            $dateFrom,
            $dateTo
        );

        $registeredIds = $registeredQuery->pluck('id');

        $registeredUsers = $registeredIds->count();

        /*
        * De esos usuarios, ¿cuántos participaron
        * dentro del mismo periodo?
        */
        $participatingQuery = UserInteraction::query()
            ->whereIn('user_id', $registeredIds);

        $this->applyDateRange(
            $participatingQuery,
            $dateFrom,
            $dateTo
        );

        $participatingUsers = $participatingQuery
            ->distinct('user_id')
            ->count('user_id');

        $rate = $registeredUsers > 0
            ? round(
                ($participatingUsers / $registeredUsers) * 100,
                2
            )
            : 0;

        return response()->json([
            'success' => true,
            'registered_users' => $registeredUsers,
            'participating_users' => $participatingUsers,
            'conversion_rate' => $rate . '%',
        ]);
    }
    //4.- Total de interacciones realizadas por todos los usuarios
    public function totalInteractions(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        return response()->json([
            'success' => true,
            'total_interactions' => $query->count(),
        ]);
    }

    //5. Cada usuario que participo realizo en promedio 'X' interacciones
    public function totalInteractionsAverage(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $totalInteractions = (clone $query)->count();

        $totalParticipants = (clone $query)
            ->distinct('user_id')
            ->count('user_id');

        $average = $totalParticipants > 0
            ? round(
                $totalInteractions / $totalParticipants,
                2
            )
            : 0;

        return response()->json([
            'success' => true,
            'average_interactions' => $average,
        ]);
    }

    //6. Total de juegos utilizados por los usuarios
    public function totalUsedGames(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        return response()->json([
            'success' => true,
            'total_used_games' =>
                $query->distinct('model_id')->count('model_id'),
        ]);
    }
    //7. El juego más utilizado por los usuarios
    public function totalMostUsedGames(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $game = $query
            ->select(
                'model_id',
                'model_title'
            )
            ->selectRaw('COUNT(*) as total_interactions')
            ->groupBy('model_id', 'model_title')
            ->orderByDesc('total_interactions')
            ->first();

        return response()->json([
            'success' => true,
            'most_used_game' => $game,
        ]);
    }
    //Total de interacciones por juego
    public function interactionsByGame(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $data = $query
            ->select(
                'model_id',
                'model_title'
            )
            ->selectRaw('COUNT(*) as total_interactions')
            ->groupBy('model_id', 'model_title')
            ->orderByDesc('total_interactions')
            ->get();

        return response()->json([
            'success' => true,
            'interactions_by_game' => $data,
        ]);
    }

    //Total de usuarios únicos por juego, siempre deberá ser 1 en total_users, ya que un usuario no puede interactuar más de una vez con un mismo juego
    public function uniqueUsersByGame(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $data = $query
            ->select(
                'model_id',
                'model_title'
            )
            ->selectRaw(
                'COUNT(DISTINCT user_id) as total_users'
            )
            ->groupBy('model_id', 'model_title')
            ->orderByDesc('total_users')
            ->get();

        return response()->json([
            'success' => true,
            'unique_users_by_game' => $data,
        ]);
    }

    //total de premios ganados por los usuarios
    public function totalAwardsWon(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query()
            ->where('hit', 1);

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        return response()->json([
            'success' => true,
            'total_awards_won' => $query->count(),
        ]);
    }

    //total de premios no ganados por los usuarios, es decir, aquellos que participaron pero no obtuvieron ningún premio
    public function totalParticipantsWithoutAward(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query()
            ->where('hit', 0);

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        return response()->json([
            'success' => true,
            'total_participants_without_award' =>
                $query->count(),
        ]);
    }

    //total en porcentaje de premios ganados por los usuarios, es decir, aquellos que participaron y ganaron premio
    public function conversionParticipantsWithAward(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $total = (clone $query)->count();

        $awards = (clone $query)
            ->where('hit', 1)
            ->count();

        $rate = $total > 0
            ? round(($awards / $total) * 100, 2)
            : 0;

        return response()->json([
            'success' => true,
            'conversion_rate' => $rate . '%',
        ]);
    }

    //Tiempo promedio de resolución por juego, es decir, el tiempo promedio que tarda un usuario en completar un juego desde que inicia hasta que termina
    public function averageResolutionTimePerGame(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $data = $query
            ->select(
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
            ->whereColumn(
                'hit_updated_at',
                '>',
                'hit_created_at'
            )
            ->groupBy(
                'model_id',
                'model_title'
            )
            ->get();

        return response()->json([
            'success' => true,
            'average_resolution_time_per_game' => $data,
        ]);
    }

    //Tiempo minimo de resolución por juego, es decir, el tiempo minimo que tarda un usuario en completar un juego desde que inicia hasta que termina
    public function minimumResolutionTimePerGame(Request $request){
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $data = $query
            ->select(
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
            ->whereColumn(
                'hit_updated_at',
                '>',
                'hit_created_at'
            )
            ->groupBy(
                'model_id',
                'model_title'
            )
            ->get();

        return response()->json([
            'success' => true,
            'minimum_resolution_time_per_game' => $data,
        ]);
    }

    //Tiempo maximo de resolución por juego, es decir, el tiempo maximo que tarda un usuario en completar un juego desde que inicia hasta que termina
    public function maximumResolutionTimePerGame(Request $request){
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $data = $query
            ->select(
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
            ->whereColumn(
                'hit_updated_at',
                '>',
                'hit_created_at'
            )
            ->groupBy(
                'model_id',
                'model_title'
            )
            ->get();

        return response()->json([
            'success' => true,
            'maximum_resolution_time_per_game' => $data,
        ]);
    }
    //El juego menos utilizado por los usuarios
    public function leastUsedGame(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $game = $query
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
            'least_used_game' => $game,
        ]);
    }
    // A que hora promedio se registran los usuarios, es decir, el promedio de la hora en que los usuarios se registran en la plataforma
   public function averageRegistrationTime(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = User::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $average = $query
            ->selectRaw('
                SEC_TO_TIME(
                    AVG(
                        TIME_TO_SEC(
                            TIME(created_at)
                        )
                    )
                ) as average_time
            ')
            ->value('average_time');

        return response()->json([
            'success' => true,
            'average_registration_time' => $average,
        ]);
    }
    //Hora con mayor cantidad de registros
    public function peakRegistrationHour(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = User::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $result = $query
            ->selectRaw('HOUR(created_at) as hour')
            ->selectRaw('COUNT(*) as total_registrations')
            ->groupByRaw('HOUR(created_at)')
            ->orderByDesc('total_registrations')
            ->first();

        return response()->json([
            'success' => true,
            'peak_registration_hour' => $result,
        ]);
    }
    //Hora con mayor cantidad de interacciones
    public function peakInteractionHour(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $result = $query
            ->selectRaw('HOUR(created_at) as hour')
            ->selectRaw('COUNT(*) as total_interactions')
            ->groupByRaw('HOUR(created_at)')
            ->orderByDesc('total_interactions')
            ->first();

        return response()->json([
            'success' => true,
            'peak_interaction_hour' => $result,
        ]);
    }
    //Registros por hora
    public function registrationsByHour(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = User::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $data = $query
            ->selectRaw('HOUR(created_at) as hour')
            ->selectRaw('COUNT(*) as total_registrations')
            ->groupByRaw('HOUR(created_at)')
            ->orderBy('hour')
            ->get();

        return response()->json([
            'success' => true,
            'registrations_by_hour' => $data,
        ]);
    }
    //Interacciones por hora y minuto, es decir, el total de interacciones realizadas por los usuarios en cada hora y minuto del día
    public function interactionsByHour(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $data = $query
            ->selectRaw("
                DATE_FORMAT(created_at, '%H:%i') as time
            ")
            ->selectRaw('COUNT(*) as total_interactions')
            ->groupByRaw("
                DATE_FORMAT(created_at, '%H:%i')
            ")
            ->orderBy('time')
            ->get();

        return response()->json([
            'success' => true,
            'interactions_by_hour' => $data,
        ]);
    }
    //Registros por día
    public function registrationsByDay(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = User::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $data = $query
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('COUNT(*) as total_registrations')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->paginate(30);

        return response()->json([
            'success' => true,
            'registrations_by_day' => $data,
        ]);
    }
    //Interacciones por día
    public function interactionsByDay(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $data = $query
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('COUNT(*) as total_interactions')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->paginate(30);

        return response()->json([
            'success' => true,
            'interactions_by_day' => $data,
        ]);
    }
    //Día de la semana con mayor actividad
    public function mostActiveWeekday(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = UserInteraction::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $result = $query
            ->selectRaw(
                'DAYOFWEEK(created_at) as weekday_number'
            )
            ->selectRaw(
                'DAYNAME(created_at) as weekday'
            )
            ->selectRaw(
                'COUNT(*) as total_interactions'
            )
            ->groupByRaw('
                DAYOFWEEK(created_at),
                DAYNAME(created_at)
            ')
            ->orderByDesc('total_interactions')
            ->first();

        return response()->json([
            'success' => true,
            'most_active_weekday' => $result,
        ]);
    }
    // Nuevos usuarios registrados en el último mes, es decir, los usuarios que se registraron en la plataforma en los últimos 30 días
    public function newUsersMonth(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = User::query();

        if ($dateFrom || $dateTo) {

            $this->applyDateRange(
                $query,
                $dateFrom,
                $dateTo
            );

        } else {

            $query
                ->whereYear(
                    'created_at',
                    now()->year
                )
                ->whereMonth(
                    'created_at',
                    now()->month
                );
        }

        return response()->json([
            'success' => true,
            'new_users' => $query->count(),
        ]);
    }
    //Registros por semana
    public function registrationsByWeek(Request $request)
    {
        [$dateFrom, $dateTo] = $this->getDateRange($request);

        $query = User::query();

        $this->applyDateRange(
            $query,
            $dateFrom,
            $dateTo
        );

        $data = $query
            ->selectRaw("
                DATE(
                    DATE_SUB(
                        created_at,
                        INTERVAL WEEKDAY(created_at) DAY
                    )
                ) as week_start
            ")
            ->selectRaw(
                'COUNT(*) as total_registrations'
            )
            ->groupBy('week_start')
            ->orderBy('week_start')
            ->paginate(30);

        return response()->json([
            'success' => true,
            'registrations_by_week' => $data,
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