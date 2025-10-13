<?php

namespace Database\Seeders;

use App\Models\Award;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketOcrAwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = Setting::first();

        $award = Award::where('awardable_id',$setting->id)->first();

        if ($award) {
            $award->delete();
        }

        Award::create([
            'title'             => 'This is your award',
            'content'           => '
            <img src="/storage/dummy_assets/800x1180.png" class="w-auto mx-auto p-3">
            <div class="rounded mx-3 p-2" style="background-color: #000000;">
            <p class="text-lg text-center text-white my-2">Present this coupon and get your discount.</p>
            </div>
            ',
            'awardable_id'      => $setting->id,
            'awardable_type'    => 'App\Models\Setting'
        ]);
    }
}
