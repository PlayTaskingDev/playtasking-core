<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserInteractionsExport implements FromCollection, WithHeadings
{
    protected $rows_collection;

    public function __construct($rows_collection)
    {
        $this->rows_collection = $rows_collection;
    }

    public function collection()
    {
        return $this->rows_collection;
    }

    public function headings(): array
    {
        return [
            'Game ID',
            'Game Title',
            'User Name',
            'Email',
            'User Created At',
            'Pivot Hit',
            'Hit Created At',
            'Hit Updated At',
            'Award Code',
        ];
    }
}
    