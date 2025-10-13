<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailVerificationNotification extends Notification
{
    use Queueable;

    protected $verificationUrl;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(trans('Verify Email Address'))
                    ->greeting(trans('Hello') . ', ' . $notifiable->name)
                    ->line(trans('Please click the button below to verify your email address.'))
                    ->line(trans('If you did not create an account, no further action is required.'))
                    ->action(trans('Verify Email Address'), $this->verificationUrl);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
