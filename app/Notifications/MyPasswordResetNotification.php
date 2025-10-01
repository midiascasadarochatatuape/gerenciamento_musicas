<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MyPasswordResetNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(config('app.url').route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset()
        ], false));

        return (new MailMessage)
                    ->subject('🔐 Reset de Senha - Sistema Louvor')
                    ->view('emails.password-reset', [
                        'user' => $notifiable,
                        'resetUrl' => $resetUrl,
                        'token' => $this->token
                    ]);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
