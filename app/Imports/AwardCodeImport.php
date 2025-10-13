<?php

namespace App\Imports;

use App\Models\AwardCode;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AwardCodeImport implements ToModel, WithValidation, WithHeadingRow, SkipsEmptyRows, WithBatchInserts, WithChunkReading
{
    use Importable;

    protected $award_id;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function  __construct($award_id)
    {
        $this->award_id = $award_id;
    }

    public function model(array $row)
    {
        return new AwardCode([
            'id'        => Str::uuid(),
            'code'      => $row['code'],
            'award_id'  => $this->award_id,
            'product'   => $row['product'] ?? null,
            'validity'  => $row['validity'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'code'      => ['required','unique:award_codes,code'],
             // Above is alias for as it always validates in batches
             '*.code'   => ['required','unique:award_codes,code'],
        ];
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
