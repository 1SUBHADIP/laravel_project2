<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminResetPasswordNotification extends Notification
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url('/admin/reset-password/' . $this->token . '?email=' . urlencode($notifiable->email));

        return (new MailMessage)
            ->subject('CCLMS Admin Password Reset')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We received a request to reset your administrator password.')
            ->line('Click the button below to set a new password.')
            ->action('Reset Password', $resetUrl)
            ->line('If you did not request this reset, you can safely ignore this email.')
            ->salutation('CCLMS Library Management System');
    }
}
