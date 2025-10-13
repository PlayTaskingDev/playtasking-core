<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\GenerateAwardCodesRequest;
use App\Http\Requests\Panel\ImportAwardCodeRequest;
use App\Imports\AwardCodeImport;
use App\Models\Award;
use App\Models\AwardCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AwardCodeController extends Controller
{
    public function index()
    {
        $awards = Award::with(['awardable'])->withCount(['codes_available','codes_delivered'])->orderBy('created_at','desc')->get();

        return response()->view('panel.award_codes.index',[
            'title'         => 'Panel | ' . trans('Award Codes'),
            'description'   => 'Admin Panel',
            'awards'        => $awards
        ]);
    }

    public function show(Award $award)
    {
        return response()->view('panel.award_codes.show',[
            'title'         => 'Panel | ' . trans('Award Codes'),
            'description'   => 'Admin Panel',
            'award'         => $award->load('awardable')
        ]);
    }

    public function download_sample()
    {
        $filePath = public_path("storage/assets/codes_sample-v2.xlsx");

        return response()->download(
            $filePath,
            'codes_sample-v2_'. now()->format('Ymd_His') .'.xlsx',
            [
                'Cache-Control' => 'no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]
        );
    }


    public function import(ImportAwardCodeRequest $request)
    {
        ini_set('upload_max_filesize', '8M');
        ini_set('post_max_size', '8M');
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '2400');

        try {
            $import = new AwardCodeImport($request->award_id);
            $import->import($request->file('file'), null, null);

            return redirect(route('awards.codes.index', ['tenant' => tenant('id')]))->with('status', trans('Codes stored successful'));

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $awards = Award::with(['awardable','codes'])->withCount(['codes_available','codes_delivered'])->get();

            return response()->view('panel.award_codes.index',[
                'title'         => 'Panel | ' . trans('Award Codes'),
                'description'   => 'Admin Panel',
                'awards'        => $awards,
                'failures'      => $failures
            ]);
        }
    }

    public function create_award_codes(GenerateAwardCodesRequest $request)
    {
        $data = $request->validated();

        if ($data['coupon_type'] == 'multiple') {
            AwardCode::create([
                'id'        => Str::uuid(),
                'code'      => $data['code'],
                'award_id'  => $data['award_id'],
                'product'   => $data['product'] ?? null,
                'validity'  => $data['validity'] ?? null,
            ]);
        } else {
            $this->generate_codes($data['award_id'],$data['quantity'],$data['product'] ?? null,$data['validity'] ?? null);
        }

        return redirect(route('awards.codes.index', ['tenant' => tenant('id')]))->with('status', trans('Codes added successful'));
    }

    private function generate_codes($award_id,$quantity,$product = null,$validity = null)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '2400');
        
        for ($i=0; $i < $quantity; $i++) { 
            AwardCode::create([
                'id'        => Str::uuid(),
                'code'      => Str::upper(Str::random(16)),
                'award_id'  => $award_id,
                'product'   => $product,
                'validity'  => $validity,
            ]);
        }
    }

    public function destroy(Award $award)
    {
        DB::table('award_codes')->where('award_id', $award->id)->delete();
        DB::table('award_user')->where('model_id', $award->id)->delete();

        return redirect(route('awards.codes.index', ['tenant' => tenant('id')]))->with('status', trans('Codes deleted successful'));
    }
}
