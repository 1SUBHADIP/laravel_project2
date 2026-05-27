<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentOtpNotification extends Notification
{
    use Queueable;

    public string $otp;

    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('CCLMS Password Reset Code')
            ->greeting('Hello ' . ($notifiable->name ?? 'Student') . ',')
            ->line('You requested a password reset for your library account.')
            ->line('Use the following code to continue:')
            ->line('')
            ->line('**' . $this->otp . '**')
            ->line('This code will expire in 10 minutes.')
            ->line('If you did not request this, please ignore this email.')
            ->salutation('CCLMS Library Management System');
    }
}
