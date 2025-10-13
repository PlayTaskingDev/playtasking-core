<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\AplazoGame;
use App\Models\Award;
use App\Models\AwardCode;
use App\Models\Campaign;
use App\Models\CampaignSplashPage;
use App\Models\CatchGame;
use App\Models\CatchObject;
use App\Models\ClickWin;
use App\Models\ContentType;
use App\Models\MemoryCard;
use App\Models\MemoryQuiz;
use App\Models\PaymentGame;
use App\Models\Puzzle;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\ShareQuiz;
use App\Models\VoteContest;
use App\Models\VoteContestAsset;
use App\Models\VoteContestVotation;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CampaignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('award_codes')->truncate();
        DB::table('award_user')->truncate();
        CampaignSplashPage::truncate();
        Award::truncate();
        Answer::truncate();
        Question::truncate();
        Quiz::truncate();
        MemoryCard::truncate();
        MemoryQuiz::truncate();
        ShareQuiz::truncate();
        VoteContestVotation::truncate();
        VoteContestAsset::truncate();
        VoteContest::truncate();
        ClickWin::truncate();
        AplazoGame::truncate();
        Puzzle::truncate();
        CatchObject::truncate();
        CatchGame::truncate();
        AwardCode::truncate();
        Campaign::truncate();

        $campaign = Campaign::factory()->create();
        $content_type_games = ContentType::where('system_name','games')->first();
        $content_type_tickets = ContentType::where('system_name','tickets')->first();
        $content_type_coupons = ContentType::where('system_name','coupons')->first();

        CampaignSplashPage::create([
            'campaign_id'           => $campaign->id,
            'featured_video_url'    => 'https://www.youtube.com/embed/oNyDux6c1dQ?si=JLR30wAuSO3NrQ3x',
            'featured_image_url'    => null,
            'instructions'          => '<p>Below you can participate for incredible <strong>instant wins</strong> and also participate for an <strong>AUDI A1 2024</strong>.</p>',
        ]);

        $campaign->content_types()->attach([$content_type_games->id,$content_type_tickets->id,$content_type_coupons->id]);

        $quiz = Quiz::create([
            'title'             => 'The most blockbuster movie',
            'description'       => 'Which of these was the most blockbuster movie this year?',
            'gradient_1'        => '#020024',
            'gradient_2'        => '#080878',
            'failed_response'   => 'Failed response.',
            'failed_image'      => '/storage/dummy_assets/award.png',
            'slug'              => 'blockbuster-movie',
            'init_date'         => Carbon::now(),
            'end_date'          => fake()->dateTimeBetween('-1 day','+30 days'),
            'featured_image'    => '/storage/dummy_assets/award.png',
            'featured_image_disabled'    => '/storage/dummy_assets/award-disabled.png',
            'campaign_id'       => $campaign->id,
            'content_type_id'   => $content_type_games->id,
            'game_banner'       => '/storage/dummy_assets/600x200.png',
            'btn_text_color'    => '#ffffff',
        ]);

        $question = Question::create([
            'title'     => 'Which of these was the most blockbuster movie?',
            'quiz_id'   => $quiz->id,
        ]);

        DB::table('answers')->insert(
            [
                [
                    'id'                => Str::uuid(),
                    'title'             => 'Mario Bros',
                    'is_correct'        => false,
                    'featured_image'    => '/storage/assets/images/quizzes/super-mario.jpg',
                    'question_id'       => $question->id,
                ],
                [
                    'id'                => Str::uuid(),
                    'title'             => 'Oppenheimer',
                    'is_correct'        => false,
                    'featured_image'    => '/storage/assets/images/quizzes/oppenheimer.jpg',
                    'question_id'       => $question->id,
                ],
                [
                    'id'                => Str::uuid(),
                    'title'             => 'Guardianes de la Galaxia',
                    'is_correct'        => false,
                    'featured_image'    => '/storage/assets/images/quizzes/guardianes.jpg',
                    'question_id'       => $question->id,
                ],
                [
                    'id'                => Str::uuid(),
                    'title'             => 'Barbie',
                    'is_correct'        => true,
                    'featured_image'    => '/storage/assets/images/quizzes/barbie.jpg',
                    'question_id'       => $question->id,
                ]
            ]
        );

        Award::create([
            'title'             => 'This is your award',
            'content'           => '
            <img src="/storage/dummy_assets/800x1180.png" class="w-auto mx-auto p-3">
            <div class="rounded mx-3 p-2" style="background-color: #000000;">
            <p class="text-lg text-center text-white my-2">Present this coupon and get your discount.</p>
            </div>
            ',
            'awardable_type'    => 'App\Models\Quiz',
            'awardable_id'      => $quiz->id,
        ]);

        $memory_quiz = MemoryQuiz::create([
            'title'             => 'Memory Quiz',
            'description'       => 'Memory Quiz Game',
            'gradient_1'        => '#ed8000',
            'gradient_2'        => '#f58c25',
            'slug'              => 'memory-quiz',
            'seconds'           => 60,
            'init_date'         => Carbon::now(),
            'end_date'          => fake()->dateTimeBetween('-1 day','+30 days'),
            'featured_image'    => '/storage/dummy_assets/award.png',
            'featured_image_disabled'    => '/storage/dummy_assets/award-disabled.png',
            'back_card_image'   => '/storage/dummy_assets/250x250.png',
            'failed_image'      => '/storage/dummy_assets/800x1180.png',
            'campaign_id'       => $campaign->id,
            'content_type_id'   => $content_type_games->id,
            'game_banner'       => '/storage/dummy_assets/600x200.png',
            'btn_text_color'    => '#ffffff',
        ]);

        DB::table('memory_cards')->insert(
            [
                [
                    'id'                => Str::uuid(),
                    'memory_quiz_id'    => $memory_quiz->id,
                    'name'              => 'vue',
                    'featured_image'    => '/storage/assets/images/memory_quizzes/vue.svg'
                ],
                [
                    'id'                => Str::uuid(),
                    'memory_quiz_id'    => $memory_quiz->id,
                    'name'              => 'angular',
                    'featured_image'    => '/storage/assets/images/memory_quizzes/angular.svg'
                ],
                [
                    'id'                => Str::uuid(),
                    'memory_quiz_id'    => $memory_quiz->id,
                    'name'              => 'aurelia',
                    'featured_image'    => '/storage/assets/images/memory_quizzes/aurelia.svg'
                ],
                [
                    'id'                => Str::uuid(),
                    'memory_quiz_id'    => $memory_quiz->id,
                    'name'              => 'backbone',
                    'featured_image'    => '/storage/assets/images/memory_quizzes/backbone.svg'
                ],
                [
                    'id'                => Str::uuid(),
                    'memory_quiz_id'    => $memory_quiz->id,
                    'name'              => 'ember',
                    'featured_image'    => '/storage/assets/images/memory_quizzes/ember.svg'
                ],
                [
                    'id'                => Str::uuid(),
                    'memory_quiz_id'    => $memory_quiz->id,
                    'name'              => 'react',
                    'featured_image'    => '/storage/assets/images/memory_quizzes/react.svg'
                ]
            ]
        );

        Award::create([
            'title'             => 'This is your award',
            'content'           => '
            <img src="/storage/dummy_assets/800x1180.png" class="w-auto mx-auto p-3">
            <div class="rounded mx-3 p-2" style="background-color: #000000;">
            <p class="text-lg text-center text-white my-2">Present this coupon and get your discount.</p>
            </div>
            ',
            'awardable_id'      => $memory_quiz->id,
            'awardable_type'    => 'App\Models\MemoryQuiz'
        ]);

        $share_quiz = ShareQuiz::create([
            'title'                 => 'Share this URL',
            'description'           => 'Share this URL in your social networks to win.',
            'slug'                  => 'share-this-url',
            'featured_image'        => '/storage/dummy_assets/award.png',
            'featured_image_disabled'    => '/storage/dummy_assets/award-disabled.png',
            'featured_video_url'    => 'https://www.youtube.com/embed/oNyDux6c1dQ?si=JLR30wAuSO3NrQ3x',
            'featured_image_url'    => '/storage/dummy_assets/share_image.jpg',
            'gradient_1'            => '#5a00ed',
            'gradient_2'            => '#9148f0',
            'share_url'             => 'https://tailwindcss.com/',
            'share_text'            => 'Tailwind the best UI framework.',
            'init_date'             => Carbon::now(),
            'end_date'              => fake()->dateTimeBetween('-1 day','+30 days'),
            'campaign_id'           => $campaign->id,
            'content_type_id'       => $content_type_games->id,
            'game_banner'       => '/storage/dummy_assets/600x200.png',
            'btn_text_color'    => '#ffffff',
        ]);

        Award::create([
            'title'             => 'This is your award',
            'content'           => '
            <img src="/storage/dummy_assets/800x1180.png" class="w-auto mx-auto p-3">
            <div class="rounded mx-3 p-2" style="background-color: #000000;">
            <p class="text-lg text-center text-white my-2">Present this coupon and get your discount.</p>
            </div>
            ',
            'awardable_id'      => $share_quiz->id,
            'awardable_type'    => 'App\Models\ShareQuiz'
        ]);

        VoteContest::create([
            'title'             => 'Vote your favorite photo',
            'description'       => 'Upload a photo and share the link with your friends.',
            'gradient_1'        => '#CB3234',
            'gradient_2'        => '#78858B',
            //'failed_response'   => 'Failed response.',
            //'failed_image'      => '/storage/dummy_assets/award.png',
            'slug'              => 'voting-contest',
            'asset_type'        => 'photo',
            'asset_kb_size'     => '12000',
            'points_per_vote'   => 1,
            'init_date'         => Carbon::now(),
            'end_date'          => fake()->dateTimeBetween('-1 day','+30 days'),
            'featured_image'    => '/storage/dummy_assets/award.png',
            'featured_image_disabled'    => '/storage/dummy_assets/award-disabled.png',
            'campaign_id'       => $campaign->id,
            'content_type_id'   => $content_type_games->id,
            'game_banner'       => '/storage/dummy_assets/600x200.png',
            'btn_text_color'    => '#ffffff',
        ]);

        $click_win = ClickWin::create([
            'title'             => 'Click and Win',
            'description'       => 'Just click in the button and win!',
            'gradient_1'        => '#D41776',
            'gradient_2'        => '#A90E30',
            'slug'              => 'click-and-win',
            'init_date'         => Carbon::now(),
            'end_date'          => fake()->dateTimeBetween('-1 day','+30 days'),
            'featured_image'    => '/storage/dummy_assets/click-and-win-active-banner-here.png',
            'featured_image_disabled'    => '/storage/dummy_assets/click-and-win-disabled-banner-here.png',
            'campaign_id'       => $campaign->id,
            'content_type_id'   => $content_type_games->id,
            //'game_banner'       => '/storage/dummy_assets/600x200.png',
            'btn_text_color'    => '#ffffff',
        ]);

        Award::create([
            'title'             => 'This is your award',
            'content'           => '
            <img src="/storage/dummy_assets/800x1180.png" class="w-auto mx-auto p-3">
            <div class="rounded mx-3 p-2" style="background-color: #000000;">
            <p class="text-lg text-center text-white my-2">Present this coupon and get your discount.</p>
            </div>
            ',
            'awardable_id'      => $click_win->id,
            'awardable_type'    => 'App\Models\ClickWin'
        ]);

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
            'title'             => 'This is your award!',
            'content'           => '
            <img src="/storage/dummy_assets/800x1180.png" class="w-auto mx-auto p-3">
            <div class="rounded mx-3 p-2" style="background-color: #000000;">
            <p class="text-lg text-center text-white my-2">Present this coupon and get your discount.</p>
            </div>
            ',
            'awardable_id'      => $aplazo_game->id,
            'awardable_type'    => 'App\Models\AplazoGame'
        ]);

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
            'awardable_type'    => 'App\Models\Puzzle'
        ]);

        $catchGame = CatchGame::create([
            'title'             => 'Catch Game',
            'description'       => 'Catch Game Description',
            'gradient_1'        => '#ed8000',
            'gradient_2'        => '#f58c25',
            'slug'              => 'catch-game',
            'seconds'           => 30,
            'max_points'        => 100,
            'points_per_object' => 10,
            'init_date'         => Carbon::now(),
            'end_date'          => fake()->dateTimeBetween('-1 day','+30 days'),
            'featured_image'    => '/storage/dummy_assets/award.png',
            'featured_image_disabled'    => '/storage/dummy_assets/award-disabled.png',
            'game_bg_image'     => '/storage/dummy_assets/catch-game-bg.jpg',
            'basket_image'      => '/storage/dummy_assets/basket.png',
            'failed_image'      => '/storage/dummy_assets/800x1180.png',
            'campaign_id'       => $campaign->id,
            'content_type_id'   => $content_type_games->id,
            'game_banner'       => '/storage/dummy_assets/600x200.png',
            'btn_text_color'    => '#ffffff',
        ]);

        CatchObject::insert([
            [
                'id'            => Str::uuid(),
                'catch_game_id' => $catchGame->id,
                'object_image'    => '/storage/dummy_assets/apple.png',
            ],
            [
                'id'            => Str::uuid(),
                'catch_game_id' => $catchGame->id,
                'object_image'  => '/storage/dummy_assets/banana.png',
            ],
            [
                'id'            => Str::uuid(),
                'catch_game_id' => $catchGame->id,
                'object_image'  => '/storage/dummy_assets/carrot.png',
            ],
            [
                'id'            => Str::uuid(),
                'catch_game_id' => $catchGame->id,
                'object_image'  => '/storage/dummy_assets/pineapple.png',
            ],
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
            'awardable_id'      => $catchGame->id,
            'awardable_type'    => 'App\Models\CatchGame'
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
