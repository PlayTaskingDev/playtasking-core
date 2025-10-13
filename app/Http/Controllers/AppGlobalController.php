<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppGlobalController extends Controller
{
    public function app_inactive()
    {
        return view('dashboard.app_inactive',[
            'title'         => trans('Inactive app'),
            'description'   => trans('At this time, the app is not active.')
        ]);
    }
}
