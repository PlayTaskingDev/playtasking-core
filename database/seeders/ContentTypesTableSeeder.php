<?php

namespace Database\Seeders;

use App\Models\ContentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        ContentType::truncate();

        DB::table('content_types')->insert(
            [
                [
                    'id'            => Str::uuid(),
                    'name'          => 'Games',
                    'system_name'   => 'games',
                    'description'   => 'In this section you can correctly answer the trivia, solve the memory puzzle and share it on your networks to win instant prizes.',
                    'icon'          => '/storage/dummy_assets/gaming-white.png',
                    'icon_active'   => '/storage/dummy_assets/gaming-active.png',
                    'section_banner'=> '/storage/dummy_assets/600x200.png',
                    'gradient_1'    => '#000000',
                    'gradient_2'    => '#000000',
                ],
                [
                    'id'            => Str::uuid(),
                    'name'          => 'Register tickets',
                    'system_name'   => 'tickets',
                    'description'   => 'In this section for entering your purchase receipt, enter the largest amount of your purchases and the more you enter, the more chances you will have to win.',
                    'icon'          => '/storage/dummy_assets/tickets-white.png',
                    'icon_active'   => '/storage/dummy_assets/tickets-active.png',
                    'section_banner'=> '/storage/dummy_assets/600x200.png',
                    'gradient_1'    => '#000000',
                    'gradient_2'    => '#000000',
                ],
                [
                    'id'            => Str::uuid(),
                    'name'          => 'Register coupons',
                    'system_name'   => 'coupons',
                    'description'   => 'In this section for entering codes, enter the largest number of available coupons and the more you enter, the more chances you will have to win.',
                    'icon'          => '/storage/dummy_assets/coupon-dark.png',
                    'icon_active'   => '/storage/dummy_assets/coupon-active.png',
                    'section_banner'=> '/storage/dummy_assets/600x200.png',
                    'gradient_1'    => '#000000',
                    'gradient_2'    => '#000000',
                ],
            ]
        );

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
    }
}
