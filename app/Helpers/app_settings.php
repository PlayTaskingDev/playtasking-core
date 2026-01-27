<?php

use Illuminate\Support\Facades\DB;
use App\Models\Option;

if(!function_exists('get_app_setting')){
    function get_app_setting($param){
        try {
            $option = Option::where('option_name',$param)->first();
            return $option->option_value;
        } catch (\Throwable $th) {
            return null;
        }
    }
}

if(!function_exists('get_time_slots')){
    function get_time_slots(){
        try {
            $time_slots = [
                [
                    'id'    => '00-am',
                    'value' => '00:00',
                ],
                [
                    'id'    => '01-am',
                    'value' => '01:00',
                ],
                [
                    'id'    => '02-am',
                    'value' => '02:00',
                ],
                [
                    'id'    => '03-am',
                    'value' => '03:00',
                ],
                [
                    'id'    => '04-am',
                    'value' => '04:00',
                ],
                [
                    'id'    => '05-am',
                    'value' => '05:00',
                ],
                [
                    'id'    => '06-am',
                    'value' => '06:00',
                ],
                [
                    'id'    => '07-am',
                    'value' => '07:00',
                ],
                [
                    'id'    => '08-am',
                    'value' => '08:00',
                ],
                [
                    'id'    => '09-am',
                    'value' => '09:00',
                ],
                [
                    'id'    => '10-am',
                    'value' => '10:00',
                ],
                [
                    'id'    => '11-am',
                    'value' => '11:00',
                ],
                [
                    'id'    => '12-pm',
                    'value' => '12:00',
                ],
                [
                    'id'    => '13-pm',
                    'value' => '13:00',
                ],
                [
                    'id'    => '14-pm',
                    'value' => '14:00',
                ],
                [
                    'id'    => '15-pm',
                    'value' => '15:00',
                ],
                [
                    'id'    => '16-pm',
                    'value' => '16:00',
                ],
                [
                    'id'    => '17-pm',
                    'value' => '17:00',
                ],
                [
                    'id'    => '18-pm',
                    'value' => '18:00',
                ],
                [
                    'id'    => '19-pm',
                    'value' => '19:00',
                ],
                [
                    'id'    => '20-pm',
                    'value' => '20:00',
                ],
                [
                    'id'    => '21-pm',
                    'value' => '21:00',
                ],
                [
                    'id'    => '22-pm',
                    'value' => '22:00',
                ],
                [
                    'id'    => '23-pm',
                    'value' => '23:00',
                ],
            ];
            return $time_slots;
        } catch (\Throwable $th) {
            return null;
        }
    }
}

if (! function_exists('get_string_coincidence')) {
    function get_string_coincidence(string $mainString, string $startSubstring, int $charactersAfter): ?string
    {
        $startIndex = strpos($mainString, $startSubstring);

        if ($startIndex === false) {
            return null;
        }

        $substringStart = $startIndex + strlen($startSubstring);
        $substring = substr($mainString, $substringStart, $charactersAfter);

        if (strlen($substring) < $charactersAfter) {
            return $substring;
        }

        return $substring;
    }
}