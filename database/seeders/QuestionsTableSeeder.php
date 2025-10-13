<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Question::truncate();

        Question::create([
            'id'        => 1,
            'title'     => '¿Cuál de las siguientes películas fue la más Takillera del año?',
            'quiz_id'   => 1
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
