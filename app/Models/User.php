<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\SendEmailVerificationNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use RahulHaque\Filepond\Traits\HasFilepond;

class User extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUuids, HasFilepond;

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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'members_number'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['short_name'];

    protected function getShortNameAttribute()
    {
        $value = $this->name;
        return Str::title(Str::limit($value, 1, '. ') . Str::betweenFirst($value, ' ', ' '));
    }


    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class)->withPivot('hit')->withTimestamps();
    }

    public function award_code()
    {
        return $this->hasMany(AwardCode::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getGames()
    {
        $games = DB::table('award_user')->select('model_id')->where('user_id',$this->id)->get();

        if ($games->count() > 0) {
            $collection = collect();
            foreach ($games as $game) {
                $collection = $collection->push($game->model_id);
            }
            return $collection;
        } else {
            return null;
        }
        
    }

    public function contest_assets()
    {
        return $this->hasMany(VoteContestAsset::class);
    }

    public function aplazo_loans()
    {
        return $this->hasMany(AplazoLoan::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = env('APP_URL') . '/' . tenant('id') . '/reset-password/' . $token . '?email=' . urlencode($this->email);

        $this->notify(new ResetPasswordNotification($url));
    }

    public function sendEmailVerificationNotification()
    {
        $verificationUrl = $this->verificationUrl($this);

        $this->notify(new SendEmailVerificationNotification($verificationUrl));
    }

    protected function verificationUrl($notifiable)
    {
        /*if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable);
        }*/

        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
                'tenant' => tenant('id')
            ]
        );
    }
}
