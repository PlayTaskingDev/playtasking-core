<?php

use App\Http\Controllers\AplazoGameController;
use App\Http\Controllers\AppGlobalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\MemoryQuizController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CatchGameController;
use App\Http\Controllers\ClickWinController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OcrTicketController;
use App\Http\Controllers\Panel\PanelQuizController;
use App\Http\Controllers\Panel\PanelMemoryQuizController;
use App\Http\Controllers\Panel\PanelMemoryCardController;
use App\Http\Controllers\Panel\PanelQuestionController;
use App\Http\Controllers\Panel\PanelAnswerController;
use App\Http\Controllers\Panel\PanelPageController;
use App\Http\Controllers\Panel\PanelController;
use App\Http\Controllers\Panel\PanelAwardController;
use App\Http\Controllers\Panel\PanelMediaElementController;
use App\Http\Controllers\Panel\AwardCodeController;
use App\Http\Controllers\Panel\PanelAplazoGameController;
use App\Http\Controllers\Panel\PanelCampaignController;
use App\Http\Controllers\Panel\PanelCampaignSplashPageController;
use App\Http\Controllers\Panel\PanelCatchGameController;
use App\Http\Controllers\Panel\PanelCatchObjectController;
use App\Http\Controllers\Panel\PanelClickWinController;
use App\Http\Controllers\Panel\PanelCodeController;
use App\Http\Controllers\Panel\PanelContentTypeController;
use App\Http\Controllers\Panel\PanelPuzzleController;
use App\Http\Controllers\Panel\PanelShareQuizController;
use App\Http\Controllers\Panel\PanelTicketAnswerController;
use App\Http\Controllers\Panel\PanelTicketController;
use App\Http\Controllers\Panel\PanelVoteContestController;
use App\Http\Controllers\Panel\TicketQuestionController;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\ShareQuizController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\VoteContestAssetController;
use App\Http\Controllers\VoteContestController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// routes/web.php, api.php or any other central route files you have

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', function () {
            return redirect('/promo');
        });
    });
}

Route::group([
    'prefix' => '/{tenant}',
    'middleware' => [InitializeTenancyByPath::class],
], function () {
    require __DIR__ . '/auth.php';

    // Guest routes
    Route::get('/', [PageController::class, 'index'])->name('welcome');
    Route::prefix('votacion')->group(function () {
        Route::get('{asset}', [VoteContestAssetController::class, 'show'])->name('asset.show');
        Route::post('{asset}/vote', [VoteContestAssetController::class, 'vote'])->name('asset.vote');
    });

    // Dashboard routes
    Route::middleware(['auth', 'app_active'])->prefix('dashboard')->group(function () {
        Route::get('/', function () {
            return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
        });

        Route::prefix('error')->group(function () {
            Route::get('/', function () {
                return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
            });
            Route::view('sin-dinamica', 'dashboard.nogame', [
                'title'         => trans('No active game'),
                'description'   => trans('At this time, there is no active game. Be aware for more quizzes.')
            ])->name('dashboard.nogame');
    
            Route::view('ya-participaste', 'dashboard.hasparticipated', [
                'title'         => trans('You have been participated in this quiz.'),
                'description'   => trans('You have been participated in this quiz. Be aware for more quizzes.')
            ])->name('dashboard.hasparticipated');
        });

        Route::prefix('dinamicas')->group(function () {
            Route::get('/', function () {
                return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
            });
            Route::get('bienvenida', [CampaignController::class, 'splash_page'])->name('campaign.splash');
            Route::get('{slug}', [CampaignController::class, 'show'])->name('campaign.show');
        });

        Route::prefix('trivias')->group(function () {
            Route::get('/', [QuizController::class, 'quiz_index'])->name('quiz.index');
            Route::get('/{slug}', [QuizController::class, 'quiz_show'])->name('quiz.show');
            Route::post('/quiz_evaluate', [QuizController::class, 'quiz_evaluate'])->name('quiz.evaluate');
        });

        Route::prefix('memoramas')->group(function () {
            Route::get('/', [MemoryQuizController::class, 'memory_quiz_index'])->name('memory_quiz.index');
            Route::get('/{slug}', [MemoryQuizController::class, 'memory_quiz_show'])->name('memory_quiz.show');
            Route::post('/memory-quiz-complete', [MemoryQuizController::class, 'memory_quiz_complete'])
                ->middleware('ajax_quiz.complete')->name('memory_quiz.complete');
        });

        Route::prefix('rompecabezas')->group(function () {
            Route::get('/', [PuzzleController::class, 'puzzle_index'])->name('puzzle.index');
            Route::get('/{slug}', [PuzzleController::class, 'puzzle_show'])->name('puzzle.show');
            Route::post('/puzzle-complete', [PuzzleController::class, 'puzzle_complete'])
                ->middleware('ajax_quiz.complete')->name('puzzle.complete');
        });

        Route::prefix('lluvia-de-objetos')->group(function () {
            Route::get('/', [CatchGameController::class, 'index'])->name('catch_game.index');
            Route::get('/{slug}', [CatchGameController::class, 'show'])->name('catch_game.show');
            Route::post('/catch-game-complete', [CatchGameController::class, 'catch_game_complete'])
                ->middleware('ajax_quiz.complete')->name('catch_game.complete');
        });

        Route::prefix('compartir')->group(function () {
            Route::get('/', [ShareQuizController::class, 'share_quiz_index'])->name('share_quiz.index');
            Route::get('/{slug}', [ShareQuizController::class, 'share_quiz_show'])->name('share_quiz.show');
            Route::post('/share-quiz-done', [ShareQuizController::class, 'share_quiz_done'])
                ->middleware('ajax_quiz.complete')->name('share_quiz.done');
        });

        Route::prefix('votacion')->group(function () {
            Route::get('/', [VoteContestController::class, 'vote_contest_index'])->name('vote_contest.index');
            Route::get('/{slug}', [VoteContestController::class, 'vote_contest_show'])->name('vote_contest.show');
            Route::post('/vote_contest_store', [VoteContestController::class, 'vote_contest_store'])->name('vote_contest.store');
            Route::get('/{slug}/ranking', [VoteContestController::class, 'vote_contest_ranking'])->name('vote_contest.ranking');
            Route::delete('/delete/{asset}', [VoteContestAssetController::class, 'destroy'])->name('vote_contest.destroy');
        });

        Route::prefix('click-y-gana')->group(function () {
            Route::get('/', [ClickWinController::class, 'index'])->name('click_win.index');
            Route::get('/{slug}', [ClickWinController::class, 'show'])->name('click_win.show');
        });

        // Tickets v1
        Route::middleware('campaign_has_tickets')->prefix('tickets')->group(function () {
            Route::get('/', [TicketController::class, 'index'])->name('tickets.index');
            Route::get('/crear', [TicketController::class, 'create'])->name('tickets.create');
            Route::post('/guardar', [TicketController::class, 'store'])->name('tickets.store');
            Route::get('/ticket-guardado', [TicketController::class, 'saved'])->name('tickets.saved');
        });

        // Tickets OCR
        Route::middleware('campaign_has_tickets')->prefix('tickets-participa')->group(function () {
            Route::get('/', [OcrTicketController::class, 'index'])->name('tickets.ocr.index');
            Route::get('/crear', [OcrTicketController::class, 'create'])->name('tickets.ocr.create');
            Route::post('/guardar', [OcrTicketController::class, 'store'])->name('tickets.ocr.store');
            Route::get('/ticket-guardado', [OcrTicketController::class, 'saved'])->name('tickets.ocr.saved');
        });

        // Aplazo
        Route::prefix('aplazo')->group(function (){
            Route::get('/', [AplazoGameController::class, 'index'])->name('aplazo.index');
            Route::get('/{slug}', [AplazoGameController::class, 'show'])->name('aplazo.show');
            Route::post('/pagar/{aplazoGame}', [AplazoGameController::class, 'gateway'])->name('aplazo.gateway');
            Route::get('payment/capture', [AplazoGameController::class, 'payment'])->name('aplazo.payment');
            Route::post('webhook', [AplazoGameController::class, 'webhook'])->name('aplazo.webhook');
        });

        Route::middleware('campaign_has_coupons')->prefix('cupones')->group(function () {
            Route::get('/', [CouponController::class, 'index'])->name('coupons.index');
            Route::get('/ingresar', [CouponController::class, 'capture'])->name('coupons.capture');
            Route::post('/validate', [CouponController::class, 'validation'])->name('coupons.validation');
            Route::get('/codigo-duplicado', [CouponController::class, 'duplicated'])->name('coupons.duplicated');
            Route::get('/codigo-incorrecto', [CouponController::class, 'incorrect'])->name('coupons.incorrect');
        });

        Route::prefix('filepond')->group(function () {
            Route::post('/', [RahulHaque\Filepond\Http\Controllers\FilepondController::class, 'process'])->name('filepond-process');
            Route::patch('/', [RahulHaque\Filepond\Http\Controllers\FilepondController::class, 'patch'])->name('filepond-patch');
            Route::get('/', [RahulHaque\Filepond\Http\Controllers\FilepondController::class, 'head'])->name('filepond-head');
            Route::delete('/', [RahulHaque\Filepond\Http\Controllers\FilepondController::class, 'revert'])->name('filepond-revert');
        });

        Route::prefix('ranking')->group(function () {
            Route::get('/', [RankingController::class, 'index'])->name('ranking.index');
        });

        Route::post('game-start', [CampaignController::class, 'record_game_start'])->name('game.start');

        Route::prefix('premios')->group(function () {
            Route::get('/beneficios-agotados', [AwardController::class, 'out_of_coupons'])->name('game.out_of_coupons');
            Route::get('/fallaste/{model_type}/{model}', [AwardController::class, 'game_failed'])->name('game.failed');
            /* Route::resource('awards', AwardController::class)->names([
                'show'  => 'dashboard.awards.show',
                'index' => 'dashboard.awards.index'
            ])->only(['index','show']); */
            Route::get('awards/{award}/{code_id?}', [AwardController::class, 'show'])->name('dashboard.awards.show');
            Route::get('awards', [AwardController::class, 'index'])->name('dashboard.awards.index');
        });
    });

    Route::middleware(['auth','app_active'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    /* ******* */

    // Admin routes
    Route::middleware(['auth', 'role:admin'])->prefix('panel')->group(function () {
        Route::get('/', [PanelController::class, 'index'])->name('panel.index');
        Route::post('/save_settings', [PanelController::class, 'save_settings'])->name('panel.settings.save');
        Route::resource('pages', PanelPageController::class);
        Route::resource('quizzes', PanelQuizController::class);
        Route::resource('memory_quizzes', PanelMemoryQuizController::class);
        Route::resource('share_quizzes', PanelShareQuizController::class);
        Route::resource('memory_cards', PanelMemoryCardController::class);
        Route::resource('questions', PanelQuestionController::class);
        Route::resource('answers', PanelAnswerController::class);
        Route::resource('awards', PanelAwardController::class);
        Route::resource('click_wins', PanelClickWinController::class);
        Route::resource('aplazo_games', PanelAplazoGameController::class);
        Route::resource('puzzles', PanelPuzzleController::class);
        Route::resource('catch_games', PanelCatchGameController::class);
        Route::resource('catch_objects', PanelCatchObjectController::class);
        Route::resource('media_elements', PanelMediaElementController::class);
        Route::get('ticketQuestion/get-codes-sample', [TicketQuestionController::class, 'download_sample'])->name('tickets.questions.sample');
        Route::get('ticketQuestion/import', [TicketQuestionController::class, 'import_show'])->name('tickets.questions.import_show');
        Route::post('ticketQuestion/import', [TicketQuestionController::class, 'import'])->name('tickets.questions.import');
        Route::resource('ticketQuestion',TicketQuestionController::class);
        Route::resource('ticketAnswer', PanelTicketAnswerController::class);
        Route::resource('vote_contest', PanelVoteContestController::class)->names(
            [
                'index'     => 'panel.vote_contest.index',
                'show'      => 'panel.vote_contest.show',
                'create'    => 'panel.vote_contest.create',
                'store'     => 'panel.vote_contest.store',
                'edit'      => 'panel.vote_contest.edit',
                'update'    => 'panel.vote_contest.update',
                'destroy'   => 'panel.vote_contest.destroy',
            ]
        );
        Route::resource('campaign',PanelCampaignController::class)->names(
            [
                'index'     => 'panel.campaign.index',
                'show'      => 'panel.campaign.show',
                'create'    => 'panel.campaign.create',
                'store'     => 'panel.campaign.store',
                'edit'      => 'panel.campaign.edit',
                'update'    => 'panel.campaign.update',
                'destroy'   => 'panel.campaign.destroy',
            ]
        );
        Route::get('codes-download', [PanelCodeController::class, 'download'])->name('codes.download');
        Route::resource('code',PanelCodeController::class);
        Route::resource('content_type',PanelContentTypeController::class);
        Route::resource('campaign_splash_page',PanelCampaignSplashPageController::class);
        Route::post('import-codes', [AwardCodeController::class, 'import'])->name('awards.codes.import');
        Route::get('import-codes/{award}', [AwardCodeController::class, 'show'])->name('awards.codes.show');
        Route::delete('destroy-codes/{award}', [AwardCodeController::class, 'destroy'])->name('awards.codes.destroy');
        Route::get('award-codes', [AwardCodeController::class, 'index'])->name('awards.codes.index');
        Route::get('get-codes-sample', [AwardCodeController::class, 'download_sample'])->name('awards.codes.sample');
        Route::post('create-award-codes', [AwardCodeController::class, 'create_award_codes'])->name('awards.create_award_codes');
        Route::get('statistics', [PanelController::class, 'statistics'])->name('panel.statistics');
        Route::prefix('tickets')->group(function () {
            Route::get('/', [PanelTicketController::class, 'index'])->name('panel.tickets.index');
            Route::get('/{model}/{id}', [PanelTicketController::class, 'show'])->name('panel.tickets.show');
            Route::delete('/{model}/{id}', [PanelTicketController::class, 'destroy'])->name('panel.tickets.destroy');
        });
        Route::get('export-user-interactions/{model_id}', [PanelController::class, 'export_user_interactions'])->name('panel.export_user_interactions');
    });

    // Pages routes
    Route::get('/promocion-inactiva', [AppGlobalController::class, 'app_inactive'])->name('app.inactive');
    Route::get('/download', [FileDownloadController::class, 'index'])->name('filedonwlad');
    Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');
    /* ******* */

});
