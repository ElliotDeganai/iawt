<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class StepValidated extends Notification
{
    use Queueable;

    public function __construct(
        public int $step,
        public bool $isLast = false,
    ) {}

    public function via(object $notifiable): array { return ['mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        $logo = \App\Models\Setting::get('logo', '/images/logo-default.svg');
        return (new MailMessage)->view('emails.step-validated', [
            'user'     => $notifiable,
            'step'     => $this->step,
            'nextStep' => $this->step + 1,
            'isLast'   => $this->isLast,
            'dashUrl'  => url('/dashboard?tab=parcours'),
            'logoUrl'  => str_starts_with($logo, 'http') ? $logo : url($logo),
            'siteName' => \App\Models\Setting::get('site_name', 'InAfrikaWeTrust'),
        ])->subject($this->isLast
            ? 'Félicitations — Votre parcours est terminé !'
            : "Étape {$this->step} validée — InAfrikaWeTrust");
    }
}