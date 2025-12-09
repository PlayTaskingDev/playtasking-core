<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SmashGame extends Model
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

    protected $with = ['award'];

    protected $appends = [
        'is_valid','only_date','model_name','table_name'
    ];

    public function getIsValidAttribute()
    {
        $validity = Carbon::createFromFormat('Y-m-d H:i:s',$this->end_date);
        $today = Carbon::now();

        return $today->isBefore($validity);
    }

    public function getOnlyDateAttribute()
    {
        $validity = Carbon::createFromFormat('Y-m-d H:i:s',$this->end_date);

        return $validity->toDateString();
    }

    public function getModelNameAttribute()
    {
        return get_class($this);
    }

    public function getTableNameAttribute(): string
    {
        return $this->getTable();
    }

    public function award()
    {
        return $this->morphOne(Award::class, 'awardable');
    }

    public function coupons()
    {
        return $this->hasManyThrough(AwardCode::class,Award::class,'awardable_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function content_type()
    {
        return $this->belongsTo(ContentType::class);
    }

    public function smash_objects()
    {
         return $this->hasMany(SmashObject::class);
    }
}
