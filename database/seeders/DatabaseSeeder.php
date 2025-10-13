<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                RolesTableSeeder::class,
                UsersTableSeeder::class,
                ContentTypesTableSeeder::class,
                CampaignsTableSeeder::class,
                PagesTableSeeder::class,
                SettingsTableSeeder::class,
                TicketOcrAwardSeeder::class,
                //TicketQuestionsTableSeeder::class,
            ]
        );

        Artisan::call('storage:link');
    }
}
