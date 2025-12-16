<?php

namespace App\Mail;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemandeRefusee extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;

    public function __construct(Demande $demande)
    {
        $this->demande = $demande;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre demande a été refusée - ' . $this->demande->num_demande,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.demande-refusee',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
