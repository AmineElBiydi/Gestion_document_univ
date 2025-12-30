<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reclamation;

class ReclamationReponse extends Mailable
{
    use Queueable, SerializesModels;

    public $reclamation;

    public function __construct(Reclamation $reclamation)
    {
        $this->reclamation = $reclamation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Réponse à votre réclamation',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reclamation-reponse',
            with: [
                'reclamation' => $this->reclamation,
                'etudiant' => $this->reclamation->etudiant,
                'demande' => $this->reclamation->demande,
                'reponse' => $this->reclamation->reponse,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
