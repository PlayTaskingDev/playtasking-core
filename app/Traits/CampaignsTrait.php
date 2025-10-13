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

    public function share_url_scrapping($post_url,$share_quiz)
    {
        $host = parse_url($post_url, PHP_URL_HOST);

        switch ($host) {
            case 'l.facebook.com':
                return Str::contains($post_url,parse_url($share_quiz->share_url, PHP_URL_HOST));
                break;

            case 'www.facebook.com':
                if (Str::contains($post_url,'share/')) {
                    return true;
                } else {
                    return Str::contains($post_url,'posts');
                }
                break;

            case 'web.facebook.com':
                return Str::contains($post_url,'share/p');
                break;
            
            case 'x.com':
                $selector = '#react-root';
                $node = 'article';
                break;
            
            default:
                return false;
        }

        $puppeteer = new Puppeteer([
            'executable_path'   => env('NODE_PATH'),
            'read_timeout'      => 10
        ]);

        $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36';

        $browser = $puppeteer->launch(
            [
                'headless'          => env('APP_ENV') == 'production' ? 'true' : 'false',
		        'executablePath' 	=> env('CHROME_PATH'),
                    'args' => [
                    '--no-sandbox',
                    '--disable-setuid-sandbox',
                    '--disable-dev-shm-usage',
                    '--incognito',
                    '--start-maximized',
                    '--user-agent=' . $user_agent
                ]
            ]
        );

        $page = $browser->newPage();

        try {
            $page->tryCatch->goto($post_url, ['waitUntil' => 'networkidle0']);

            $page->waitForSelector($selector);

            $data = $page->evaluate(JsFunction::createWithBody('
                const elements = document.querySelectorAll("'.$node.'");
                return Array.from(elements).map(element => element.innerText);
            '));

            $browser->close();

            if (isset($data[0])) {
                return Str::contains($data[0],$share_quiz->share_text);
            } else {
                return false;
            }
            
        } catch (Node\Exception $exception) {
            return false;
        }
    }
}
