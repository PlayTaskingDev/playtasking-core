<?php

namespace Database\Seeders;

use App\Models\Award;
use App\Models\FlappyGame;
use App\Models\ContentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Traits\CampaignsTrait;
use Carbon\Carbon;

class FlappyGamesTableSeeder extends Seeder
{
    use CampaignsTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        FlappyGame::truncate();

        $campaign = $this->get_current_campaign();
        $content_type_games = ContentType::where('system_name','games')->first();

         $flappyGame = FlappyGame::create([
            'title'             => 'Flappy Game',
            'description'       => 'Flappy Game Description',
            'gradient_1'        => '#ed8000',
            'gradient_2'        => '#f58c25',
            'slug'              => 'flappy-game',
            'max_points'        => 100,
            'points_per_pipe'   => 10,
            'init_date'         => Carbon::now(),
            'end_date'          => fake()->dateTimeBetween('-1 day','+30 days'),
            'featured_image'    => '/storage/dummy_assets/award.png',
            'featured_image_disabled'    => '/storage/dummy_assets/award-disabled.png',
            'game_bg_image'     => '/storage/dummy_assets/catch-game-bg.jpg',
            'game_pipe_image'   => '/storage/dummy_assets/pipe-bg.jpg',
            'flappy_image'      => '/storage/dummy_assets/basket.png',
            'failed_image'      => '/storage/dummy_assets/800x1180.png',
            'campaign_id'       => $campaign->id,
            'content_type_id'   => $content_type_games->id,
            'game_banner'       => '/storage/dummy_assets/600x200.png',
            'btn_text_color'    => '#ffffff',
        ]);

         Award::create([
            'title'             => 'This is your award!',
            'content'           => '
            <p><img class="w-auto mx-auto" src="/storage/dummy_assets/800x1180.png"></p>
            <div class="mt-3">
            <div id="message" class="text-black text-start p-5 rounded-lg block relative" style="background-color: #ffffff; z-index: 3;"><strong>GANASTE</strong></div>
            <div id="code" class="text-black text-start p-5 rounded-lg -mt-2 block relative" style="background-color: #ff0000; z-index: 2;"><strong>FOLIO</strong></div>
            <div id="validity" class="text-black text-start p-5 rounded-lg -mt-2 block relative" style="background-color: #0000ff; z-index: 1;"><strong>VIGENCIA</strong></div>
            </div>
            ',
            'awardable_id'      => $flappyGame->id,
            'awardable_type'    => 'App\Models\FlappyGame'
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}