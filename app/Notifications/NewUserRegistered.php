<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification
{
    use Queueable;

    public function __construct(
        public User $newUser
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle inscription — ' . $this->newUser->first_name . ' ' . $this->newUser->last_name)
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Un nouvel utilisateur vient de s\'inscrire sur InAfrikaWeTrust.')
            ->line('**Nom :** ' . $this->newUser->first_name . ' ' . $this->newUser->last_name)
            ->line('**E-mail :** ' . $this->newUser->email)
            ->action('Voir les utilisateurs', url('/admin/users'))
            ->line('Vous recevez cet e-mail car vous êtes administrateur de la plateforme.');
    }
}
