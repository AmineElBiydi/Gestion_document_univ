<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\DemandeSoumise;
use App\Mail\DemandeValidee;
use App\Mail\DemandeRefusee;
use App\Mail\ReclamationRecue;
use App\Models\Demande;
use App\Models\Reclamation;

class EmailService
{
    /**
     * Envoyer email de confirmation de demande soumise
     */
    public function envoyerConfirmationDemande(Demande $demande)
    {
        try {
            Mail::to($demande->etudiant->email)->send(new DemandeSoumise($demande));
            \Log::info('Email confirmation demande envoyé: ' . $demande->num_demande);
            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email confirmation demande: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer email de validation de demande
     */
    public function envoyerValidationDemande(Demande $demande)
    {
        try {
            $pdfPath = null;
            
            // Generate PDF for convention de stage
            if ($demande->type_document === 'convention_stage') {
                $pdfGenerator = new ConventionStagePDF();
                $pdfPath = $pdfGenerator->generate($demande);
            }
            
            Mail::to($demande->etudiant->email)->send(new DemandeValidee($demande, $pdfPath));
            \Log::info('Email validation demande envoyé: ' . $demande->num_demande);
            
            // Clean up temporary PDF file
            if ($pdfPath && file_exists($pdfPath)) {
                unlink($pdfPath);
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email validation demande: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer email de refus de demande
     */
    public function envoyerRefusDemande(Demande $demande)
    {
        try {
            Mail::to($demande->etudiant->email)->send(new DemandeRefusee($demande));
            \Log::info('Email refus demande envoyé: ' . $demande->num_demande);
            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email refus demande: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer email de confirmation de réclamation reçue
     */
    public function envoyerConfirmationReclamation(Reclamation $reclamation)
    {
        try {
            Mail::to($reclamation->etudiant->email)->send(new ReclamationRecue($reclamation));
            \Log::info('Email confirmation réclamation envoyé: ' . $reclamation->id);
            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email confirmation réclamation: ' . $e->getMessage());
            return false;
        }
    }
}
