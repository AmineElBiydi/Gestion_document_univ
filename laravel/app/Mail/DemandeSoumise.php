<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Demande;

class DemandeSoumise extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;

    /**
     * Create a new message instance.
     */
    public function __construct(Demande $demande)
    {
        $this->demande = $demande;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre demande - ' . $this->demande->num_demande,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.demande-soumise',
            with: [
                'demande' => $this->demande,
                'etudiant' => $this->demande->etudiant,
                'typeDocument' => $this->getTypeDocumentLabel(),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    private function getTypeDocumentLabel(): string
    {
        $labels = [
            'attestation_scolaire' => 'Attestation de scolarité',
            'attestation_reussite' => 'Attestation de réussite',
            'releve_notes' => 'Relevé de notes',
            'convention_stage' => 'Convention de stage',
        ];

        return $labels[$this->demande->type_document] ?? 'Document';
    }
}
