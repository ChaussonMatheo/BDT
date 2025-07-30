<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\RendezVous;

class FactureRendezVousMail extends Mailable
{
    use Queueable, SerializesModels;

    public $rendezVous;
    public $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(RendezVous $rendezVous, $pdfContent)
    {
        $this->rendezVous = $rendezVous;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Facture - Prestations de service - ' . ($this->rendezVous->guest_name ?? 'Client'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.facture-rendezvous',
            with: ['rendezVous' => $this->rendezVous]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        // Générer un nom de fichier avec le nom du client et la date
        $clientName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $this->rendezVous->guest_name ?? 'Client');
        $serviceDate = \Carbon\Carbon::parse($this->rendezVous->date_heure)->format('Y-m-d');
        $fileName = "Facture_{$clientName}_{$serviceDate}.pdf";

        return [
            Attachment::fromData(fn () => $this->pdfContent, $fileName)
                ->withMime('application/pdf'),
        ];
    }
}
