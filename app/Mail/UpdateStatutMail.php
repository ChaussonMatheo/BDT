<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\RendezVous;

class UpdateStatutMail extends Mailable
{
    use Queueable, SerializesModels;

    public $rendezVous;
    public $oldStatus;

    public function __construct(RendezVous $rendezVous, $oldStatus)
    {
        $this->rendezVous = $rendezVous;
        $this->oldStatus = $oldStatus;
    }

    public function build()
    {
        return $this->subject("Mise à jour de votre rendez-vous - B-CLEAN")
            ->view('emails.updateStatutMail')
            ->with([
                'manageUrl' => url('/rendezvous/' . $this->rendezVous->token .  '/info'),
                'icsLink' => url('/rendezvous/' . $this->rendezVous->token . '/download-ics'),
            ]);
    }
}
