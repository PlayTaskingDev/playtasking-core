<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory, HasUuids;

    // Set the key type to UUID
    protected $keyType = 'string'; 
    
    // Disable auto-incrementing
    public $incrementing = false; 

    /**
     * The "booting" function of model
     *
     * @return void
     */
    public static function boot() {
        parent::boot();
        // Auto generate UUID when creating data User
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['only_date'];

    public function getOnlyDateAttribute()
    {
        $validity = Carbon::createFromFormat('Y-m-d H:i:s',$this->end_date);

        return $validity->toDateString();
    }

    public function content_types()
    {
        return $this->belongsToMany(ContentType::class);
    }

    public function campaign_splash_page()
    {
        return $this->hasOne(CampaignSplashPage::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function memory_quizzes()
    {
        return $this->hasMany(MemoryQuiz::class);
    }

    public function share_quizzes()
    {
        return $this->hasMany(ShareQuiz::class);
    }

    public function vote_contests()
    {
        return $this->hasMany(VoteContest::class);
    }

    public function click_wins()
    {
        return $this->hasMany(ClickWin::class);
    }

    public function aplazo_games()
    {
        return $this->hasMany(AplazoGame::class);
    }

    public function puzzles()
    {
        return $this->hasMany(Puzzle::class);
    }

    public function catch_games()
    {
        return $this->hasMany(CatchGame::class);
    }

    public function smash_games()
    {
        return $this->hasMany(SmashGame::class);
    }

    public function code()
    {
        return $this->hasOne(Code::class);
    }
}
