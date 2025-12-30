<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reclamation;
use App\Models\Etudiant;

class ReclamationReponse extends Mailable
{
    use Queueable, SerializesModels;

    public $reclamation;
    public $etudiant;
    public $typeReclamation;
    public $reponse;
    public $adminNom;

    public function __construct(
        Reclamation $reclamation, 
        Etudiant $etudiant, 
        string $typeReclamation, 
        string $reponse,
        string $adminNom
    )
    {
        $this->reclamation = $reclamation;
        $this->etudiant = $etudiant;
        $this->typeReclamation = $typeReclamation;
        $this->reponse = $reponse;
        $this->adminNom = $adminNom;
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
                'etudiant' => $this->etudiant,
                'typeReclamation' => $this->typeReclamation,
                'reponse' => $this->reponse,
                'adminNom' => $this->adminNom,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
