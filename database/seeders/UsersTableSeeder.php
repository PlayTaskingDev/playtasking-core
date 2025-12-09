<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        User::truncate();

        User::create(
            [
                'name'              => 'Alberto',
                'email'             => 'desarrollo@playtasking.com',
                'phone'             => '5575012750',
                'email_verified_at' => now(),
                'password'          => Hash::make('D3sarr0llo2*'),
            ]
        )->assignRole('admin');

        User::factory(4)->create();

        Artisan::call('rankings:update');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
