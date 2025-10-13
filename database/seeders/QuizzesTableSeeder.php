<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Award;

class QuizzesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /* empty resources/images/faker directory
        $process = new Process(['find', storage_path() . '/app/public/assets/images/faker/', '-type', 'f', '-delete']);
        $process->run();

        Quiz::factory()->has(
            Question::factory()->has(
                Answer::factory()->count(3)
            )->count(5)
        )->count(3)->create(); */
        
    }
}
