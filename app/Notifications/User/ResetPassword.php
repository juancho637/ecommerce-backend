<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    private $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $token)
    {
        $this->token = $token;
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
            ->greeting(__('Dear :name,', ['name' => explode(' ', trim($notifiable->name))[0]]))
            ->subject(__('Reset Password Notification'))
            ->line(__('We received a password reset request for your account. To reset your password open the application and enter the following token:'))
            ->line($this->token)
            ->line(__('This token will expire in :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.token_expire')]))
            ->line(__('If you did not request a password reset, no further action is required.'))
            ->salutation(__('Thank you for using :app', ['app' => config('app.name')]));
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
