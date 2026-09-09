<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationResult extends Notification
{
    use Queueable;

    public function __construct(
        public Application $application,
        public string $result, // 'accepted' or 'rejected'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $logo = \App\Models\Setting::get('logo', '/images/logo-default.svg');

        return (new MailMessage)->view('emails.application-result', [
            'user'        => $notifiable,
            'application' => $this->application,
            'result'      => $this->result,
            'reason'      => $this->application->rejection_reason,
            'dashboardUrl'=> url('/dashboard'),
            'logoUrl'     => str_starts_with($logo, 'http') ? $logo : url($logo),
            'siteName'    => \App\Models\Setting::get('site_name', 'InAfrikaWeTrust'),
        ])->subject($this->result === 'accepted'
            ? 'Félicitations — Votre candidature est acceptée !'
            : 'Réponse à votre candidature — InAfrikaWeTrust');
    }
}
