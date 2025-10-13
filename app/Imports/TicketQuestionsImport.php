<?php

namespace App\Imports;

use App\Models\TicketQuestion;
use App\Models\TicketAnswer;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Str;

class TicketQuestionsImport implements OnEachRow, WithValidation, WithHeadingRow, SkipsEmptyRows
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onRow(Row $row)
    {
        $question = TicketQuestion::create([
            'id'        => Str::uuid(),
            'title'     => $row['title'],
        ]);

        TicketAnswer::create([
            'id'                    => Str::uuid(),
            'title'                 => $row['answer_1_correct'],
            'is_correct'            => true,
            'ticket_question_id'    => $question->id,
        ]);

        TicketAnswer::create([
            'id'                    => Str::uuid(),
            'title'                 => $row['answer_2'],
            'is_correct'            => false,
            'ticket_question_id'    => $question->id,
        ]);

        TicketAnswer::create([
            'id'                    => Str::uuid(),
            'title'                 => $row['answer_3'],
            'is_correct'            => false,
            'ticket_question_id'    => $question->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'title'                 => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ“”’,.;:!"¡?¿#\(\)\' \-]+$/'],
             // Above is alias for as it always validates in batches
             '*.title'              => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ“”’,.;:!"¡?¿#\(\)\' \-]+$/'],
             'answer_1_correct'     => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ“”’,.;:!"¡?¿#\(\)\' \-]+$/'],
             '*.answer_1_correct'   => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ“”’,.;:!"¡?¿#\(\)\' \-]+$/'],
             'answer_2'             => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ“”’,.;:!"¡?¿#\(\)\' \-]+$/'],
             '*.answer_2'           => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ“”’,.;:!"¡?¿#\(\)\' \-]+$/'],
             'answer_3'             => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ“”’,.;:!"¡?¿#\(\)\' \-]+$/'],
             '*.answer_3'           => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ“”’,.;:!"¡?¿#\(\)\' \-]+$/'],
        ];
    }
}
