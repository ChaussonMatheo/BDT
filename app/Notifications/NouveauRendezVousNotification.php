<?php
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NouveauRendezVousNotification extends Notification
{
    public $rendezVous;

    public function __construct($rendezVous)
    {
        $this->rendezVous = $rendezVous;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $prestation = $this->rendezVous->prestation->service ?? 'Non spécifiée';
        $client = $this->rendezVous->user->name ?? $this->rendezVous->guest_name ?? 'Client invité';

        return (new MailMessage)
            ->subject('🆕 Nouveau rendez-vous créé')
            ->greeting('Bonjour Admin,')
            ->line("Un nouveau rendez-vous vient d’être enregistré.")
            ->line("Client : {$client}")
            ->line("Prestation : {$prestation}")
            ->line("Date et heure : " . \Carbon\Carbon::parse($this->rendezVous->date_heure)->format('d/m/Y H:i'))
            ->action('Voir le rendez-vous', url('/dashboard'))
            ->line('Merci de votre vigilance.');
    }
}
