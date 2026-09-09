<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class StepRework extends Notification
{
    use Queueable;

    public function __construct(
        public int $step,
        public string $reason,
    ) {}

    public function via(object $notifiable): array { return ['mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        $logo = \App\Models\Setting::get('logo', '/images/logo-default.svg');
        return (new MailMessage)->view('emails.step-rework', [
            'user'     => $notifiable,
            'step'     => $this->step,
            'reason'   => $this->reason,
            'dashUrl'  => url('/dashboard?tab=parcours'),
            'logoUrl'  => str_starts_with($logo, 'http') ? $logo : url($logo),
            'siteName' => \App\Models\Setting::get('site_name', 'InAfrikaWeTrust'),
        ])->subject("Étape {$this->step} à retravailler — InAfrikaWeTrust");
    }
}
