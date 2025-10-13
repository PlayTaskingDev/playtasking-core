<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'ocr_ticket_phrases' => 'array',
    ];

    public function award()
    {
        return $this->morphOne(Award::class, 'awardable');
    }

}
