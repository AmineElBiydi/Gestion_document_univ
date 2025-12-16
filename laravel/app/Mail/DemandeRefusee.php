<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemandeRefusee extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $etudiant;
    public $typeDocument;
    public $raisonRefus;

    public function __construct($demande, $etudiant, $typeDocument, $raisonRefus)
    {
        $this->demande = $demande;
        $this->etudiant = $etudiant;
        $this->typeDocument = $typeDocument;
        $this->raisonRefus = $raisonRefus;
    }

    public function build()
    {
        return $this->subject('Demande Non Validée - Campus Admin Connect')
                    ->view('emails.demande-refusee');
    }
}