<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReclamationRecue extends Mailable
{
    use Queueable, SerializesModels;

    public $reclamation;
    public $etudiant;
    public $typeReclamation;

    public function __construct($reclamation, $etudiant, $typeReclamation)
    {
        $this->reclamation = $reclamation;
        $this->etudiant = $etudiant;
        $this->typeReclamation = $typeReclamation;
    }

    public function build()
    {
        return $this->subject('Réclamation Reçue - Campus Admin Connect')
                    ->view('emails.reclamation-recue');
    }
}