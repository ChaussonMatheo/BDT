<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\GarageReservation;

class FactureGarageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(GarageReservation $reservation, $pdfContent)
    {
        $this->reservation = $reservation;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Facture - B-CLEAN - ' . $this->reservation->garage->nom,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.facture-garage',
            with: ['reservation' => $this->reservation]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        // Générer un nom de fichier avec le nom du garage et la date
        $garageName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $this->reservation->garage->nom);
        $startDate = \Carbon\Carbon::parse($this->reservation->start_date)->format('Y-m-d');
        $fileName = "Facture_{$garageName}_{$startDate}.pdf";

        return [
            Attachment::fromData(fn () => $this->pdfContent, $fileName)
                ->withMime('application/pdf'),
        ];
    }
}
