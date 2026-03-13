<?php

namespace Database\Seeders;

use App\Models\Award;
use App\Models\ContentType;
use App\Models\Puzzle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Traits\CampaignsTrait;
use Carbon\Carbon;

class PuzzlesTableSeeder extends Seeder
{
    use CampaignsTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Puzzle::truncate();
        $campaign = $this->get_current_campaign();
        $content_type_games = ContentType::where('system_name','games')->first();

        $puzzle = Puzzle::create([
            'title'             => 'Puzzle',
            'description'       => 'Puzzle Game',
            'gradient_1'        => '#ed8000',
            'gradient_2'        => '#f58c25',
            'slug'              => 'puzzle-quiz',
            'seconds'           => 60,
            'pieces'            => 12,
            'init_date'         => Carbon::now(),
            'end_date'          => fake()->dateTimeBetween('-1 day','+30 days'),
            'featured_image'    => '/storage/dummy_assets/award.png',
            'featured_image_disabled'    => '/storage/dummy_assets/award-disabled.png',
            'puzzle_image'      => '/storage/dummy_assets/puzzle.jpg',
            'failed_image'      => '/storage/dummy_assets/800x1180.png',
            'campaign_id'       => $campaign->id,
            'content_type_id'   => $content_type_games->id,
            'game_banner'       => '/storage/dummy_assets/600x200.png',
            'btn_text_color'    => '#ffffff',
        ]);

        Award::create([
            'title'             => 'This is your award!',
            'content'           => '
            <img src="/storage/dummy_assets/800x1180.png" class="w-auto mx-auto p-3">
            <div class="rounded mx-3 p-2" style="background-color: #000000;">
            <p class="text-lg text-center text-white my-2">Present this coupon and get your discount.</p>
            </div>
            ',
            'awardable_id'      => $puzzle->id,
            'awardable_type'    => \App\Models\Puzzle::class
        ]);
    }
}
