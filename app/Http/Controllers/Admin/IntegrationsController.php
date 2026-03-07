<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;

class IntegrationsController extends Controller
{
    public function index()
    {
        $options  = Option::all();
        $settings = [];
        foreach ($options as $k => $o){
            $settings[$o->option_name] = $o->option_value;
        }

        return view('admin.options', [
            'title'         => get_app_setting('app_name'),
            'description'   => get_app_setting('app_description'),
            'settings'      => json_decode(json_encode($settings))
        ]);
        
    }

    public function save(Request $request)
    {
          foreach($request->all() as $k => $v){
            if($k !== '_token'){
                Option::updateOrCreate(
                    ['option_name' => $k],
                    ['option_value' => $v]
                );
            }
        }

        return redirect(route('v2.options', ['tenant' => tenant('id')]))->with('status', trans('Settings saved successful'));
    }
}
