<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Demande;

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
            view: 'emails.demande-refusee',
            with: [
                'demande' => $this->demande,
                'etudiant' => $this->demande->etudiant,
                'typeDocument' => $this->demande->getTypeDocumentLabel(),
                'raisonRefus' => $this->demande->raison_refus,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
