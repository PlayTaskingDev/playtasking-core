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
                $selector = '#react-root';
                $node = 'article';
                break;

            default:
                return false;
        }

        // Chrome necesita un HOME escribible para www-data
        putenv('HOME=' . env('CHROME_HOME', '/tmp/chrome-home'));
        putenv(
            'XDG_CONFIG_HOME=' .
            env('CHROME_CONFIG_HOME', '/tmp/chrome-home/.config')
        );
        putenv(
            'XDG_CACHE_HOME=' .
            env('CHROME_CACHE_HOME', '/tmp/chrome-home/.cache')
        );

        // Evita los errores de DBus que vimos en el servidor headless
        putenv('DBUS_SESSION_BUS_ADDRESS=/dev/null');

        // Directorio independiente para esta ejecución
        $chromeDataDir = '/tmp/chrome-data-' . uniqid();

        try {
            $puppeteer = new Puppeteer([
                'executable_path' => env('NODE_PATH'),
                'read_timeout' => 60,
            ]);

            $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' .
                'AppleWebKit/537.36 (KHTML, like Gecko) ' .
                'Chrome/124.0.0.0 Safari/537.36';

            $browser = $puppeteer->launch([
                'headless' => true,
                'executablePath' => env('CHROME_PATH'),

                'args' => [
                    '--no-sandbox',
                    '--disable-setuid-sandbox',
                    '--disable-dev-shm-usage',
                    '--disable-gpu',

                    '--disable-background-networking',
                    '--disable-component-update',
                    '--disable-sync',
                    '--disable-default-apps',
                    '--disable-extensions',

                    '--no-first-run',
                    '--no-default-browser-check',

                    '--user-data-dir=' . $chromeDataDir,

                    '--user-agent=' . $user_agent,
                ],
            ]);

            if (!$browser) {
                \Log::error('Puppeteer launch returned null', [
                    'node' => env('NODE_PATH'),
                    'chrome' => env('CHROME_PATH'),
                ]);

                return false;
            }

            $page = $browser->newPage();

            $page->tryCatch->goto(
                $post_url,
                [
                    'waitUntil' => 'networkidle0',
                    'timeout' => 60000,
                ]
            );

            $page->waitForSelector(
                $selector,
                [
                    'timeout' => 30000,
                ]
            );

            $data = $page->evaluate(
                JsFunction::createWithBody('
                    const elements = document.querySelectorAll("' . $node . '");
                    return Array.from(elements).map(
                        element => element.innerText
                    );
                ')
            );

            $browser->close();

            if (!isset($data[0])) {
                return false;
            }

            return Str::contains(
                $data[0],
                $share_quiz->share_text
            );

        } catch (\Throwable $exception) {

            \Log::error('Puppeteer scraping error', [
                'url' => $post_url,
                'message' => $exception->getMessage(),
            ]);

            return false;

        } finally {
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
