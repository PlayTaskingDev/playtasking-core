<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Award' => 'App\Policies\AwardModelPolicy',
        'App\Models\VoteContestAsset' => 'App\Policies\VoteContestAssetModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject(trans('Verify Email Address'))
                ->greeting(trans('Hello') . ', ' . $notifiable->name)
                ->line(trans('Click the button below to verify your email address.'))
                ->action(trans('Verify Email Address'), $url);
        });
    }
}
