<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AccountCreatedByAdmin extends Notification
{
    use Queueable;

    public function __construct(
        public string $plainPassword,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre compte InAfrikaWeTrust a été créé')
            ->view('emails.account-created', [
                'newUser'       => $notifiable,
                'plainPassword' => $this->plainPassword,
                'loginUrl'      => url('/login'),
                'logoUrl'       => url(\App\Models\Setting::get('logo', '/images/logo-default.svg')),
                'siteName'      => \App\Models\Setting::get('site_name', 'InAfrikaWeTrust'),
            ]);
    }
}