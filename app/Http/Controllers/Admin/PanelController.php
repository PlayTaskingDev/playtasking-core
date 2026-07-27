<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AwardCode;
use App\Models\Award;
use App\Models\Ticket;

class PanelController extends Controller
{
    public function index()
    {
        $users = User::count();
        $top_ten_users = User::select(['name','ranking','points','email'])
            ->where('points','>',0)
            ->whereNotNull('ranking')
            ->orderBy('ranking','asc')
            ->limit(10)->get();
        $coupons = AwardCode::count();
        $coupons_delivered = AwardCode::where('active',true)->count();
        $coupons_dynamic = Award::withCount('codes_delivered')->with('awardable')->get();
        $tickets = Ticket::count();

        return view('admin.statistics', [
            'title'             => get_app_setting('app_name'),
            'description'       => get_app_setting('app_description'),
            'users'             => $users,
            'top_ten_users'     => $top_ten_users,
            'coupons'           => $coupons,
            'coupons_delivered' => $coupons_delivered,
            'coupons_dynamic'   => $coupons_dynamic,
            'coupons_remaining' => $coupons - $coupons_delivered,
            'tickets'           => $tickets
        ]);
        
    }


}
