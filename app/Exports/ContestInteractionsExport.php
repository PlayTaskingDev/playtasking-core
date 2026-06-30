<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContestInteractionsExport implements FromCollection, WithHeadings
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
            'User ID',
            'User Name',
            'Email',
            'Description',
            'Image URL',
            'Submission Date'
        ];
    }
}
    