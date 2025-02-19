<?php
namespace App\Mail;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RendezVousConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $rendezVous;

    /**
     * Créer une nouvelle instance du mail.
     */
    public function __construct(RendezVous $rendezVous)
    {
        $this->rendezVous = $rendezVous;
    }

    /**
     * Construire le message.
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Confirmation de votre rendez-vous')
            ->view('emails.rendezvous_confirmation')
            ->with([
                'date' => $this->rendezVous->date_heure,
                'service' => optional($this->rendezVous->prestation)->service,
                'garage' => optional($this->rendezVous->garage)->nom ?? 'N/A'
            ]);
    }
}
