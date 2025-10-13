<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Code extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id','created_at','updated_at'];

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

    public function getModelNameAttribute()
    {
        return get_class($this);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function award()
    {
        return $this->morphOne(Award::class, 'awardable');
    }

    public function coupons()
    {
        return $this->hasManyThrough(AwardCode::class,Award::class,'awardable_id');
    }
}
