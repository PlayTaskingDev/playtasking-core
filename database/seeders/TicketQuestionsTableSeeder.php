<?php

namespace Database\Seeders;

use App\Models\TicketAnswer;
use App\Models\TicketQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TicketQuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TicketAnswer::truncate();
        TicketQuestion::truncate();

        $ticket_question = TicketQuestion::create([
            'title' => '¿En qué año se fundó Cinépolis?'
        ]);

        DB::table('ticket_answers')->insert(
            [
                [
                    'id'                => Str::uuid(),
                    'title'             => '1997',
                    'is_correct'        => true,
                    'ticket_question_id'    => $ticket_question->id,
                ],
                [
                    'id'                => Str::uuid(),
                    'title'             => '1971',
                    'is_correct'        => false,
                    'ticket_question_id'    => $ticket_question->id,
                ],
                [
                    'id'                => Str::uuid(),
                    'title'             => '2004',
                    'is_correct'        => false,
                    'ticket_question_id'    => $ticket_question->id,
                ],
            ]
        );
    }
}
