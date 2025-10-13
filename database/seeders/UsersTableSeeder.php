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
                'name'              => 'Cesar',
                'email'             => 'cesar.arciniega@gmail.com',
                'phone'             => '5519490799',
                'email_verified_at' => now(),
                'password'          => Hash::make('YhwhNis1777'),
            ]
        )->assignRole('admin');

        User::create(
            [
                'name'              => 'Jorge',
                'email'             => 'jorge.valencia@hostland.com.mx',
                'phone'             => '5519490799',
                'email_verified_at' => now(),
                'password'          => Hash::make('wQ6cU2lZ1lJ3sC4p'),
            ]
        )->assignRole('admin');

        User::factory(10)->create();

        Artisan::call('rankings:update');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
