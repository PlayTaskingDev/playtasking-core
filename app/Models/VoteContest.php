<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VoteContest extends Model
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

    protected $appends = [
        'is_valid','only_date','model_name','mb_size'
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

    public function getMbSizeAttribute()
    {
        return intval($this->asset_kb_size) / 1000;
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function content_type()
    {
        return $this->belongsTo(ContentType::class);
    }

    public function contest_assets()
    {
        return $this->hasMany(VoteContestAsset::class)->orderBy('points','desc');
    }

    public function votations()
    {
        return $this->hasManyThrough(VoteContestVotation::class,VoteContestAsset::class);
    }
}
