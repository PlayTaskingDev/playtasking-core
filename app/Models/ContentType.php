<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContentType extends Model
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

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }
}
