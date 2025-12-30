<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\DemandeSoumise;
use App\Mail\DemandeValidee;
use App\Mail\DemandeRefusee;
use App\Mail\ReclamationRecue;
use App\Models\Demande;
use App\Models\Reclamation;
use App\Mail\ReclamationReponse;

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
            $pdfPath = null;
            $pdfService = app(\App\Services\PDFService::class);
            
            // Générer le PDF
            try {
                $pdfPath = $pdfService->generatePDF($demande);
            } catch (\Exception $e) {
                \Log::error('Erreur génération PDF via EmailService: ' . $e->getMessage());
                // Continue sans PDF si la génération échoue
            }
            
            Mail::to($demande->etudiant->email)->send(new DemandeValidee(
                $demande, 
                $demande->etudiant, 
                $demande->getTypeDocumentLabel(), 
                $pdfPath
            ));
            \Log::info('Email validation demande envoyé: ' . $demande->num_demande);
            
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

    /**
     * Envoyer email de réponse à une réclamation
     */
    public function envoyerReponseReclamation(Reclamation $reclamation)
    {
        try {
            Mail::to($reclamation->etudiant->email)->send(new ReclamationReponse($reclamation));
            \Log::info('Email réponse réclamation envoyé: ' . $reclamation->id);
            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email réponse réclamation: ' . $e->getMessage());
            return false;
        }
    }
}
