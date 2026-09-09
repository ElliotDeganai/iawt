<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class StepSubmittedForAdmin extends Notification
{
    use Queueable;

    public function __construct(
        public Application $application,
        public int $step,
    ) {}

    public function via(object $notifiable): array { return ['mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        $logo = \App\Models\Setting::get('logo', '/images/logo-default.svg');
        $candidate = $this->application->user;
        return (new MailMessage)->view('emails.step-submitted-admin', [
            'admin'     => $notifiable,
            'candidate' => $candidate,
            'step'      => $this->step,
            'reviewUrl' => url("/admin/applications/{$this->application->id}"),
            'logoUrl'   => str_starts_with($logo, 'http') ? $logo : url($logo),
            'siteName'  => \App\Models\Setting::get('site_name', 'InAfrikaWeTrust'),
        ])->subject("Étape {$this->step} à examiner — {$candidate->first_name} {$candidate->last_name}");
    }
}
