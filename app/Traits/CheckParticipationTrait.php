<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait CheckParticipationTrait {

    public function check_participation($model_id, $model_type, $user_id, $hit = null)
    {
        return AwardUser::query()
        ->where('model_id', $model_id)
        ->where('model_type', $model_type)
        ->where('user_id', $user_id)
        ->when(!is_null($hit), fn($q) => $q->where('hit', $hit))
        ->first(['id', 'award_id']); 

        return $query;
    }

}