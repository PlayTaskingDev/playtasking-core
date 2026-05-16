<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionsSeederAutologinOption extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('options')->insert(
            [
                'option_name'    => 'autologin',
                'option_value'   => '0',
            ]
        );
    }
}
