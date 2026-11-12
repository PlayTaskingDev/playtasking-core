<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TriviaResponse extends Model
{
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'was_answered' => 'boolean',
        'is_correct' => 'boolean',
    ];

    public function userInteraction()
    {
        return $this->belongsTo(
            UserInteraction::class
        );
    }

    public function quiz()
    {
        return $this->belongsTo(
            Quiz::class
        );
    }

    public function question()
    {
        return $this->belongsTo(
            Question::class
        );
    }

    public function answer()
    {
        return $this->belongsTo(
            Answer::class
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }
}