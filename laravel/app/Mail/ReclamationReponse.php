<?php

// File: app/Mail/ReclamationReponse.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReclamationReponse extends Mailable
{
    use Queueable, SerializesModels;

    public $reclamation;
    public $etudiant;
    public $typeReclamation;
    public $reponseMessage;
    public $adminNom;
    public $actionsPrises;

    public function __construct($reclamation, $etudiant, $typeReclamation, $reponseMessage, $adminNom = null, $actionsPrises = null)
    {
        $this->reclamation = $reclamation;
        $this->etudiant = $etudiant;
        $this->typeReclamation = $typeReclamation;
        $this->reponseMessage = $reponseMessage;
        $this->adminNom = $adminNom;
        $this->actionsPrises = $actionsPrises;
    }

    public function build()
    {
        return $this->subject('Réponse à votre Réclamation #' . $this->reclamation->id . ' - Campus Admin Connect')
                    ->view('emails.reclamation-reponse');
    }
}