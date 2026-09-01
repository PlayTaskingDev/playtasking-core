<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\GenerateAwardCodesRequest;
use App\Http\Requests\Panel\ImportAwardCodeRequest;
use App\Imports\AwardCodeImport;
use App\Models\Award;
use App\Models\AwardCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\Panel\GenerateRandomAwardCodesRequest;
use App\Services\Admin\AwardCodeService;

class AwardCodesController extends Controller
{
    public function index()
    {
        $awards = Award::with(['awardable'])->withCount(['codes_available','codes_delivered'])->orderBy('created_at','desc')->get();
        return response()->view('admin.awardcodes.list',[
            'title'         => 'Panel | ' . trans('Award Codes'),
            'description'   => 'Admin Panel',
            'awards'        => $awards
        ]);
    }

    public function show($id)
    {
        $award = Award::findOrFail($id);
        return response()->view('admin.awardcodes.import',[
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

    public function generateRandom(
        Award $award,
        GenerateRandomAwardCodesRequest $request,
        AwardCodeService $awardCodeService
    ) {
        $data = $request->validated();

        $generated = $awardCodeService->generate(
            $award,
            $data['quantity'],
            $data['product'] ?? null,
            $data['validity'] ?? null
        );

        return redirect()
            ->route(
                'awardcodes.show',
                [
                    'tenant' => tenant('id'),
                    'awardcode' => $award,
                ]
            )
            ->with(
                'status',
                "{$generated} códigos generados correctamente."
            );
    }
    public function import(ImportAwardCodeRequest $request)
    {
        ini_set('upload_max_filesize', '8M');
        ini_set('post_max_size', '8M');

        try {
            $import = new AwardCodeImport($request->award_id);
            $import->import($request->file('file'), null, null);

            return redirect(route('awardcodes.index', ['tenant' => tenant('id')]))->with('status', trans('Codes stored successful'));

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $awards = Award::with(['awardable','codes'])->withCount(['codes_available','codes_delivered'])->get();

            return response()->view('admin.awardcodes.list',[
                'title'         => 'Panel | ' . trans('Award Codes'),
                'description'   => 'Admin Panel',
                'awards'        => $awards,
                'failures'      => $failures
            ]);
        }
    }

    public function create_award_codes(
        GenerateAwardCodesRequest $request,
        AwardCodeService $awardCodeService
    ) {
        $data = $request->validated();

        $award = Award::findOrFail(
            $data['award_id']
        );

        /*
        * Código reutilizable existente.
        */
        if ($data['coupon_type'] === 'multiple') {

            AwardCode::create([
                'code' => $data['code'],
                'award_id' => $award->id,
                'product' => $data['product'] ?? null,
                'validity' => $data['validity'] ?? null,
            ]);

        } else {

            $awardCodeService->generate(
                $award,
                $data['quantity'],
                $data['product'] ?? null,
                $data['validity'] ?? null
            );
        }

        return redirect()
            ->route(
                'awardcodes.index',
                [
                    'tenant' => tenant('id')
                ]
            )
            ->with(
                'status',
                trans('Codes added successful')
            );
    }



    public function destroy(Award $award)
    {
        DB::table('award_codes')->where('award_id', $award->id)->delete();
        DB::table('award_user')->where('model_id', $award->id)->delete();

        return redirect(route('awardcodes.index', ['tenant' => tenant('id')]))->with('status', trans('Codes deleted successful'));
    }
}
