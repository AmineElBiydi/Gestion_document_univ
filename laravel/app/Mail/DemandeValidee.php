<?php

namespace App\Mail;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class DemandeValidee extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $pdfPath;

    public function __construct(Demande $demande, $pdfPath = null)
    {
        $this->demande = $demande;
        $this->pdfPath = $pdfPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre demande a été validée - ' . $this->demande->num_demande,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.demande-validee',
        );
    }

    public function attachments(): array
    {
        if ($this->pdfPath) {
            // Déterminer le nom du fichier selon le type
            $filename = 'document.pdf';
            switch ($this->demande->type_document) {
                case 'releve_notes':
                    $filename = 'Releve_Notes.pdf';
                    break;
                case 'attestation_scolaire':
                    $filename = 'Attestation_Scolarite.pdf';
                    break;
                case 'attestation_reussite':
                    $filename = 'Attestation_Reussite.pdf';
                    break;
                case 'convention_stage':
                    $filename = 'Convention_Stage.pdf';
                    break;
            }
            
            return [
                Attachment::fromStorage($this->pdfPath)
                    ->as($filename)
                    ->withMime('application/pdf'),
            ];
        }
        return [];
    }
}
