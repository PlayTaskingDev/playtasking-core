<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveAwardRequest;
use App\Models\Award;
use App\Services\Admin\AwardService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AwardsController extends Controller
{
    public function create(Request $request)
    {
        return view('admin.awards', [
            'award' => new Award(),
            'awardable_id' => $request->query('awardable_id'),
            'awardable_type' => $request->query('awardable_type'),
        ]);
    }

    public function store(
        SaveAwardRequest $request,
        AwardService $awardService
    ) {
        $awardable = $this->resolveAwardable(
            $request->awardable_type,
            $request->awardable_id
        );

        $award = $awardService->saveFor(
            $awardable,
            $request->validated()
        );

        return redirect()
            ->route(
                'v2awards.edit',
                [
                    'tenant' => tenant('id'),
                    'v2award' => $award,
                ]
            )
            ->with(
                'status',
                trans('Award saved successful')
            );
    }

    public function edit($id)
    {
        $award = Award::query()
            ->with('awardable')
            ->findOrFail($id);

        return view('admin.awards', [
            'award' => $award,
        ]);
    }

    public function update(
        $id,
        SaveAwardRequest $request,
        AwardService $awardService
    ) {
        $award = Award::findOrFail($id);

        $awardService->update(
            $award,
            $request->validated()
        );

        return redirect()
            ->route(
                'v2awards.edit',
                [
                    'tenant' => tenant('id'),
                    'v2award' => $award,
                ]
            )
            ->with(
                'status',
                trans('Award saved successful')
            );
    }

    private function resolveAwardable(
        string $type,
        string $id
    ): Model {
        if (
            !class_exists($type)
            ||
            !is_subclass_of($type, Model::class)
        ) {
            throw ValidationException::withMessages([
                'awardable_type' =>
                    'El tipo de dinámica no es válido.',
            ]);
        }

        $awardable = $type::find($id);

        if (!$awardable) {
            throw ValidationException::withMessages([
                'awardable_id' =>
                    'La dinámica asociada no existe.',
            ]);
        }

        if (!method_exists($awardable, 'award')) {
            throw ValidationException::withMessages([
                'awardable_type' =>
                    'Esta dinámica no admite premios.',
            ]);
        }

        return $awardable;
    }
}