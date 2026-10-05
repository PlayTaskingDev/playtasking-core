<?php

namespace App\Traits;

use App\Models\Campaign;
use App\Models\ContentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;
use Illuminate\Support\Str;
use Nesk\Rialto\Exceptions\Node;

trait CampaignsTrait
{

    public function has_content_type($campaign_id, $content_type_name)
    {
        $content_type = ContentType::where('system_name', $content_type_name)->first();

        if ($content_type) {
            $query = DB::table('campaign_content_type')->where([['campaign_id', $campaign_id], ['content_type_id', $content_type->id]])->first();

            if ($query) {
                return $content_type;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function get_current_campaign()
    {
        $now = Carbon::now()->toDateTimeString();
        return Campaign::where([['active',true],['init_date','<',$now],['end_date','>',$now]])->firstOrFail();
    }

    public function share_url_scrapping($post_url, $share_quiz)
    {
        $host = parse_url($post_url, PHP_URL_HOST);

        switch ($host) {
            case 'l.facebook.com':
                return Str::contains(
                    $post_url,
                    parse_url($share_quiz->share_url, PHP_URL_HOST)
                );

            case 'www.facebook.com':
                if (Str::contains($post_url, 'share/')) {
                    return true;
                }

                return Str::contains($post_url, 'posts');

            case 'web.facebook.com':
                return Str::contains($post_url, 'share/p');

            case 'x.com':
            case 'www.x.com':
            case 'twitter.com':
            case 'www.twitter.com':
                break;

            default:
                return false;
        }

        $chromeHome = env('CHROME_HOME', '/tmp/chrome-home');
        $chromeDataDir = '/tmp/chrome-data-' . uniqid();

        putenv('HOME=/tmp/chrome-home');
        putenv('XDG_CONFIG_HOME=/tmp/chrome-home/.config');
        putenv('XDG_CACHE_HOME=/tmp/chrome-home/.cache');

        $browser = null;

        try {
            $puppeteer = new Puppeteer([
                'executable_path' => env('NODE_PATH'),
                'read_timeout' => 60,
                'log_node_console' => true,
            ]);

            $browser = $puppeteer->launch([
                'headless' => true,
                'executablePath' => env('CHROME_PATH'),
                'args' => [
                    '--no-sandbox',
                    '--disable-dev-shm-usage',
                ],
            ]);

            dd($browser);
        if (!$browser) {
            \Log::error('Puppeteer launch returned null', [
                'node' => env('NODE_PATH'),
                'chrome' => env('CHROME_PATH'),
                'home' => getenv('HOME'),
                'xdg_config' => getenv('XDG_CONFIG_HOME'),
                'xdg_cache' => getenv('XDG_CACHE_HOME'),
                'chrome_data' => $chromeDataDir,
            ]);

            return false;
        }

            $page = $browser->newPage();

            $page->setUserAgent($userAgent);

            $page->goto($post_url, [
                'waitUntil' => 'domcontentloaded',
                'timeout' => 60000,
            ]);

            // X carga bastante contenido después del DOM inicial.
            sleep(5);

            $pageText = $page->evaluate(
                JsFunction::createWithBody('
                    return document.body
                        ? document.body.innerText
                        : "";
                ')
            );

            if (!$pageText) {
                \Log::warning('X scraping sin contenido', [
                    'url' => $post_url,
                ]);

                return false;
            }

            // Útil mientras terminamos de afinar la validación.
            \Log::info('X scraping response', [
                'url' => $post_url,
                'text' => mb_substr($pageText, 0, 5000),
            ]);

            /*
            * Detectamos algunas respuestas comunes de X
            * que significan que realmente no cargó el post.
            */
            $blockedTexts = [
                'Something went wrong',
                'Try reloading',
                'Log in to X',
                'Sign up for X',
            ];

            foreach ($blockedTexts as $blockedText) {
                if (Str::contains($pageText, $blockedText)) {
                    \Log::warning('X no permitió visualizar el post', [
                        'url' => $post_url,
                        'reason' => $blockedText,
                    ]);

                    return false;
                }
            }

            $expectedText = trim($share_quiz->share_text);

            if ($expectedText === '') {
                return false;
            }

            return Str::contains(
                Str::lower($pageText),
                Str::lower($expectedText)
            );

        } catch (\Throwable $exception) {
            \Log::error('Puppeteer scraping error', [
                'url' => $post_url,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return false;

        } finally {
            try {
                if ($browser) {
                    $browser->close();
                }
            } catch (\Throwable $e) {
                // Evitamos que un error al cerrar Chrome afecte la respuesta.
            }

            if (is_dir($chromeDataDir)) {
                exec(
                    'rm -rf ' .
                    escapeshellarg($chromeDataDir)
                );
            }
        }
    }

    public function out_of_time_validation($game_start, $max_time_seconds)
    {
        $game_start_time = Carbon::createFromFormat('Y-m-d H:i:s.u', $game_start);

        return $game_start_time->diffInSeconds(Carbon::now()) > $max_time_seconds;
    }

    
     public function signature_hash($gameId){
        $secretKey = ENV('APP_KEY');
        return hash_hmac('sha256', $gameId, $secretKey);
    }

     public function calculate_ranking_time($hit_date_created,$hit_date_updated){
        $fecha1 = Carbon::parse($hit_date_created);
        $fecha2 = Carbon::parse($hit_date_updated);
        $diferenciaEnSegundos = $fecha1->floatDiffInSeconds($fecha2);
        return (float) number_format($diferenciaEnSegundos, 2, '.', '');
    }

    public function get_all_active_games($slug){
        $now = Carbon::now()->toDateTimeString();
        return Campaign::with(
                    [
                        'share_quizzes' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'quizzes' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'memory_quizzes' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'vote_contests' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'click_wins' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'aplazo_games' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'puzzles' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'catch_games' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'smash_games' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'flappy_games' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'penal_games' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            }
                    ])
                    ->where([['slug', $slug],['active',true],['init_date','<',$now],['end_date','>',$now]])
                    ->first();
    }

    public function get_user_interactions($model_id){
        return DB::table("user_interactions as ui")->where('ui.model_id', $model_id)
            ->join('users as u', 'u.id', '=', 'ui.user_id')
            ->selectRaw('
                ui.model_id as game_id,
                ui.model_title as game_title,
                u.name as user_name,
                u.email,
                u.created_at as user_created_at,
                ui.hit as pivot_hit,
                ui.hit_created_at as hit_created_at,
                ui.hit_updated_at as hit_updated_at,
                ui.code as award_code
            ')
            ->get();
    }
}
