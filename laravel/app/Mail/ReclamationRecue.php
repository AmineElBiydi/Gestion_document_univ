<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reclamation;

class ReclamationRecue extends Mailable
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
            subject: 'Confirmation de réception de votre réclamation',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reclamation-recue',
            with: [
                'reclamation' => $this->reclamation,
                'etudiant' => $this->reclamation->etudiant,
                'demande' => $this->reclamation->demande,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
