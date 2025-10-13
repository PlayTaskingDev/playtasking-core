<?php

namespace App\Http\Controllers\Panel;

use App\Enums\CodeTypeEnum;
use App\Exports\AwardCodeExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveCodeRequest;
use App\Models\Campaign;
use App\Models\Code;
use App\Models\ContentType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\UploadImageTrait;

class PanelCodeController extends Controller
{
    use UploadImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $codes = Code::with(['award'])->orderBy('created_at','desc')->get();

        return view('panel.coupons.index', [
            'title'         => 'Panel | ' . trans('Coupons'),
            'description'   => 'Admin Panel',
            'codes'         => $codes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = new Code();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','coupons')->first();
        $code_types = CodeTypeEnum::values();
        $time_slots = get_time_slots();

        return view('panel.coupons.edit', [
            'code'          => $code,
            'campaigns'     => $campaigns,
            'content_type'  => $content_type,
            'code_types'    => $code_types,
            'time_slots'    => $time_slots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveCodeRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','codes',$request->file('featured_image'));
        }
        
        Code::create($data);

        return redirect(route('code.index', ['tenant' => tenant('id')]))->with('status', trans('Coupon saved successful'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function show(Code $code)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function edit(Code $code)
    {
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','coupons')->first();
        $code_types = CodeTypeEnum::values();
        $time_slots = get_time_slots();

        return view('panel.coupons.edit', [
            'code'          => $code,
            'campaigns'     => $campaigns,
            'content_type'  => $content_type,
            'code_types'    => $code_types,
            'time_slots'    => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Code $code)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','codes',$request->file('featured_image'));
        }

        if( !$request->has('active') ){
            $data['active'] = false;
        }

        $code->fill($data);
        $code->save();
        
        return redirect(route('code.index', ['tenant' => tenant('id')]))->with('status', trans('Coupon saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function destroy(Code $code)
    {
        $code->loadCount('coupons');

        if ($code->coupons_count != 0) {
            return redirect(route('code.index', ['tenant' => tenant('id')]))->with('status', trans('Coupon can not be deleted because it has codes related.'));
        }

        $code->delete();

        return redirect(route('code.index', ['tenant' => tenant('id')]))->with('status', trans('Coupon deleted successful'));
    }

    public function download()
    {
        return Excel::download(new AwardCodeExport, 'codes_'. now()->format('Ymd_His') .'.xlsx');
    }
}
