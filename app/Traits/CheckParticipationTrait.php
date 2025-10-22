<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait CheckParticipationTrait {

    public function check_participation($model_id, $model_type, $user_id, $hit = null)
    {
        if (is_null($hit)) {
            $query = DB::table('award_user')->where([['model_id',$model_id],['model_type',$model_type],['user_id',$user_id]])->first();
        } else {
            $query = DB::table('award_user')->where([['model_id',$model_id],['model_type',$model_type],['user_id',$user_id],['hit',$hit]])->first();
        }

        return $query;
    }
}