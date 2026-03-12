<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Traits\UploadImageTrait;

class BrandingController extends Controller
{

    use UploadImageTrait;

    public function index()
    {
        $branding  = Option::all();
        $settings = [];
        foreach ($branding as $k => $o){
            $settings[$o->option_name] = $o->option_value;
        }

        return view('admin.branding', [
            'title'         => get_app_setting('app_name'),
            'description'   => get_app_setting('app_description'),
            'settings'      => json_decode(json_encode($settings))
        ]);
        
    }

    public function save(Request $request)
    {
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
        return redirect(route('v2.branding', ['tenant' => tenant('id')]))->with('status', trans('Branding saved successful'));
    }
}
