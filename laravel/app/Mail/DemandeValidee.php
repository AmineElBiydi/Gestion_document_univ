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
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            return [
                Attachment::fromPath($this->pdfPath)
                    ->as('Convention_Stage.pdf')
                    ->withMime('application/pdf'),
            ];
        }
        return [];
    }
}
