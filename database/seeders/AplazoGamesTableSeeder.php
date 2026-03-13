<?php

namespace Database\Seeders;

use App\Models\AplazoGame;
use App\Models\Award;
use App\Models\ContentType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Traits\CampaignsTrait;

class AplazoGamesTableSeeder extends Seeder
{
    use CampaignsTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AplazoGame::truncate();
        $campaign = $this->get_current_campaign();
        $content_type_games = ContentType::where('system_name','games')->first();

        $aplazo_game = AplazoGame::create([
            'title'             => 'Aplazo Loan',
            'description'       => 'Get a product with Aplazo and win!',
            'gradient_1'        => '#D41776',
            'gradient_2'        => '#A90E30',
            'slug'              => 'aplazo-loan',
            'init_date'         => Carbon::now(),
            'end_date'          => fake()->dateTimeBetween('-1 day','+30 days'),
            'promo_image'       => '/storage/dummy_assets/800x1180.png',
            'featured_image'    => '/storage/dummy_assets/award.png',
            'featured_image_disabled'    => '/storage/dummy_assets/award-disabled.png',
            'price'             => 100.00,
            'product_name'      => 'This is an amazing product',
            'product_description'   => 'Pay for 3 and get 2! Nice promotion, uh?',
            'campaign_id'       => $campaign->id,
            'content_type_id'   => $content_type_games->id,
            'game_banner'       => '/storage/dummy_assets/600x200.png',
            'btn_text_color'    => '#ffffff',
        ]);

        Award::create([
            'title'             => 'This is your fucking award!',
            'content'           => '
            <img src="/storage/dummy_assets/800x1180.png" class="w-auto mx-auto p-3">
            <div class="rounded mx-3 p-2" style="background-color: #000000;">
            <p class="text-lg text-center text-white my-2">Present this coupon and get your discount.</p>
            </div>
            ',
            'awardable_id'      => $aplazo_game->id,
            'awardable_type'    => \App\Models\AplazoGame::class
        ]);
    }
}
