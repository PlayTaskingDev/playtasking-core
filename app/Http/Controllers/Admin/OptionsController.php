<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Traits\UploadImageTrait;

class OptionsController extends Controller
{
    use UploadImageTrait;

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
        if( !$request->has('social_login_active') ){
            $request['social_login_active'] = false;
        }

        if( !$request->has('app_active') ){
            $request['app_active'] = false;
        }
        if( !$request->has('ranking_enabled') ){
            $request['ranking_enabled'] = false;
        }
        if( !$request->has('ranking_enabled_games') ){
            $request['ranking_enabled_games'] = false;
        }
        if( !$request->has('ranking_enabled_tickets') ){
            $request['ranking_enabled_tickets'] = false;
        }

        if( !$request->has('members_number') ){
            $request['members_number'] = false;
        }
        if( !$request->has('allow_city') ){
            $request['allow_city'] = false;
        }

        if( !$request->has('cards_shadow') ){
            $request['cards_shadow'] = false;
        }
        foreach($request->all() as $k => $v){
        if($k !== '_token'){
            if(!is_string($v) && (!empty($v) && $v->isFile())){
                $v = $this->uploadImage('gcs','settings',$request->file($k));
                $v = str_replace('\\', '/', $v);
            }
            Option::updateOrCreate(
                    ['option_name' => $k],
                    ['option_value' => $v]
                );
            }
        }

        return redirect(route('v2.options', ['tenant' => tenant('id')]))->with('status', trans('Settings saved successful'));
    }
}
