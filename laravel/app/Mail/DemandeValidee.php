<?php

// File: app/Mail/DemandeValidee.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemandeValidee extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $etudiant;
    public $typeDocument;

    public function __construct($demande, $etudiant, $typeDocument)
    {
        $this->demande = $demande;
        $this->etudiant = $etudiant;
        $this->typeDocument = $typeDocument;
    }

    public function build()
    {
        return $this->subject('Demande Validée - Campus Admin Connect')
                    ->view('emails.demande-validee');
    }
}