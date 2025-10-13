<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShareQuiz extends Model
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

    protected $appends = ['is_valid','only_date','table_name'];

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

    public function getTableNameAttribute(): string
    {
        return $this->getTable();
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function award()
    {
        return $this->morphOne(Award::class, 'awardable');
    }

    public function content_type()
    {
        return $this->belongsTo(ContentType::class);
    }
}
