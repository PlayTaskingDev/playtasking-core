<?php

namespace App\Http\Controllers\Admin\Games;

use App\Exports\ContestInteractionsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveVoteContestRequest;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Models\VoteContest;
use App\Traits\UploadImageTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class VoteGameController extends Controller
{
    use UploadImageTrait;

    public function index()
    {
        $voteContests = VoteContest::query()
            ->latest()
            ->get();

        return view('admin.games.votegame.list', [
            'title' => 'Panel | ' . trans('Vote Contests'),
            'description' => 'Admin Panel',
            'vote_contests' => $voteContests,
        ]);
    }

    public function create()
    {
        $voteContest = new VoteContest();

        return view('admin.games.votegame.edit', [
            'vote_contest' => $voteContest,
            'campaigns' => Campaign::all(),
            'type_asset' => $this->getAssetTypes(),
            'content_type' => $this->getGamesContentType(),
            'time_slots' => get_time_slots(),
        ]);
    }

    public function store(
        SaveVoteContestRequest $request
    ) {
        $data = $this->prepareVoteContestData(
            $request
        );

        $voteContest = VoteContest::create(
            $data
        );

        return redirect()
            ->route('votegames.edit', [
                'tenant' => tenant('id'),
                'votegame' => $voteContest,
            ])
            ->with(
                'status',
                trans('Vote contest saved successful')
            );
    }

    public function edit($id)
    {
        $voteContest = VoteContest::query()
            ->with('campaign')
            ->findOrFail($id);

        return view('admin.games.votegame.edit', [
            'vote_contest' => $voteContest,
            'campaigns' => Campaign::all(),
            'type_asset' => $this->getAssetTypes(),
            'content_type' => $this->getGamesContentType(),
            'time_slots' => get_time_slots(),
        ]);
    }

    public function update(
        $id,
        SaveVoteContestRequest $request
    ) {
        $voteContest = VoteContest::findOrFail(
            $id
        );
        $data = $this->prepareVoteContestData(
            $request
        );

        if (
            $request->boolean(
                'delete_image_holder_hidden'
            )
        ) {
            $data['game_banner'] = null;
        }

        $voteContest->update(
            $data
        );

        return redirect()
            ->route('votegames.edit', [
                'tenant' => tenant('id'),
                'votegame' => $voteContest,
            ])
            ->with(
                'status',
                trans('Vote contest saved successful')
            );
    }

    public function export($model_id)
    {
        $rowsCollection = DB::table(
            'vote_contest_assets as vca'
        )
            ->where(
                'vca.vote_contest_id',
                $model_id
            )
            ->join(
                'users as u',
                'u.id',
                '=',
                'vca.user_id'
            )
            ->selectRaw('
                u.id as user_id,
                u.name as user_name,
                u.email as user_email,
                vca.title as description,
                vca.asset_url as asset_url,
                vca.created_at as submission_date
            ')
            ->get();

        return Excel::download(
            new ContestInteractionsExport(
                $rowsCollection
            ),
            'user_interactions_contests.xlsx'
        );
    }

    public function destroy($id)
    {
        $voteContest = VoteContest::query()
            ->with('contest_assets.votations')
            ->findOrFail($id);

        DB::transaction(
            function () use ($voteContest) {

                foreach (
                    $voteContest->contest_assets
                    as $contestAsset
                ) {
                    foreach (
                        $contestAsset->votations
                        as $votation
                    ) {
                        $votation->delete();
                    }

                    $contestAsset->delete();
                }

                $voteContest->delete();
            }
        );

        return redirect()
            ->route('votegames.index', [
                'tenant' => tenant('id'),
            ])
            ->with(
                'status',
                trans('Vote contest deleted successful')
            );
    }

    private function prepareVoteContestData(
        SaveVoteContestRequest $request
    ): array {
        $imageFields = [
            'featured_image',
            'featured_image_disabled',
            'game_banner',
        ];

        $data = Arr::except(
            $request->validated(),
            array_merge(
                $imageFields,
                [
                    'delete_image_holder_hidden',
                ]
            )
        );

        foreach ($imageFields as $field) {

            if (!$request->hasFile($field)) {
                continue;
            }

            $data[$field] = $this->uploadImage(
                'gcs',
                'vote_contests',
                $request->file($field)
            );
        }

        /*
         * El administrador captura MB.
         * En BD actualmente se conserva multiplicado por 1000.
         */
        $data['asset_kb_size'] =
            (float) $request->input(
                'asset_kb_size'
            ) * 1000;


        /*
         * Los switches no marcados no llegan en el POST.
         */
        $data['show_ranking'] =
            $request->boolean(
                'show_ranking'
            );

        $data['btn_border'] =
            $request->boolean(
                'btn_border'
            );

        $data['btn_shadow'] =
            $request->boolean(
                'btn_shadow'
            );

        $data['btn_enable_shadow'] =
            $request->boolean(
                'btn_enable_shadow'
            );

        return $data;
    }

    private function getAssetTypes(): array
    {
        return [
            (object) [
                'id' => 'photo',
                'name' => 'Photo',
            ],

            (object) [
                'id' => 'video',
                'name' => 'Video',
            ],
        ];
    }

    private function getGamesContentType(): ContentType
    {
        return ContentType::query()
            ->where(
                'system_name',
                'games'
            )
            ->firstOrFail();
    }
}