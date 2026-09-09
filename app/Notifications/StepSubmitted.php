<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class StepSubmitted extends Notification
{
    use Queueable;

    public function __construct(public int $step) {}

    public function via(object $notifiable): array { return ['mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        $logo = \App\Models\Setting::get('logo', '/images/logo-default.svg');
        return (new MailMessage)->view('emails.step-submitted', [
            'user'     => $notifiable,
            'step'     => $this->step,
            'logoUrl'  => str_starts_with($logo, 'http') ? $logo : url($logo),
            'siteName' => \App\Models\Setting::get('site_name', 'InAfrikaWeTrust'),
        ])->subject("Étape {$this->step} soumise — InAfrikaWeTrust");
    }
}
