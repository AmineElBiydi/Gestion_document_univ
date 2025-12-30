<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Demande;
use App\Models\Etudiant;

class DemandeValidee extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $etudiant;
    public $typeDocument;
    public $pdfPath;

    public function __construct(Demande $demande, Etudiant $etudiant, string $typeDocument, string $pdfPath = null)
    {
        $this->demande = $demande;
        $this->etudiant = $etudiant;
        $this->typeDocument = $typeDocument;
        $this->pdfPath = $pdfPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre demande de ' . $this->typeDocument . ' a été validée',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.demande-validee',
            with: [
                'demande' => $this->demande,
                'etudiant' => $this->etudiant,
                'typeDocument' => $this->typeDocument,
            ]
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $filename = $this->typeDocument . '_' . $this->demande->num_demande . '.pdf';
            $attachments[] = Attachment::fromPath($this->pdfPath)
                ->as($filename)
                ->withMime('application/pdf');
        }
        
        return $attachments;
    }
}
