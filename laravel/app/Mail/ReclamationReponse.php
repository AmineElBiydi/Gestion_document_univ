<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Reclamation;
use App\Models\Etudiant;

class ReclamationReponse extends Mailable
{
    use Queueable, SerializesModels;

    public $reclamation;
    public $etudiant;

    public function __construct(Reclamation $reclamation, Etudiant $etudiant)
    {
        $this->reclamation = $reclamation;
        $this->etudiant = $etudiant;
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
                'typeReclamation' => $this->reclamation->type,
                'reponse' => $this->reclamation->reponse,
                'adminNom' => $this->reclamation->traiteParAdmin 
                    ? $this->reclamation->traiteParAdmin->prenom . ' ' . $this->reclamation->traiteParAdmin->nom 
                    : 'Administration',
            ]
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        // Add document attached by admin in response
        if ($this->reclamation->piece_jointe_reponse_path) {
            $path = storage_path('app/public/' . $this->reclamation->piece_jointe_reponse_path);
            if (file_exists($path)) {
                $attachments[] = Attachment::fromPath($path);
            }
        }

        return $attachments;
    }
}
