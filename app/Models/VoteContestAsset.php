<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class VoteContestAsset extends Model
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
    
    protected $appends = ['iframe_video_url'];

    public function getIframeVideoUrlAttribute()
    {
        if (Str::contains($this->asset_url,'vimeo')) {
            $url_components = parse_url($this->asset_url);
            $path = Str::of($url_components['path'])->trim('/');
            $iframe_video_url = 'https://player.vimeo.com/video/' . $path . '?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479';
        } else {
            $iframe_video_url = null;
        }
        
        return $iframe_video_url;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vote_contest()
    {
        return $this->belongsTo(VoteContest::class);
    }

    public function votations()
    {
        return $this->hasMany(VoteContestVotation::class);
    }
}
