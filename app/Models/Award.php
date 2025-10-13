<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Award extends Model
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

    protected $appends = ['model_type'];

    public function awardable()
    {
        return $this->morphTo();
    }

    public function codes()
    {
        return $this->hasMany(AwardCode::class);
    }

    public function codes_available()
    {
        return $this->hasMany(AwardCode::class)->where('active',0);
    }

    public function codes_delivered()
    {
        return $this->hasMany(AwardCode::class)->where('active',1);
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'award_user','award_id','user_id')->withPivot('hit')->withTimestamps();
    }

    public function getModelTypeAttribute()
    {
        return Str::lower(class_basename($this->awardable_type));
    }
}
