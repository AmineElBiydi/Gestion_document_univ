<?php

namespace App\Http\Controllers;

use App\Mail\DemandeValidee;
use App\Mail\DemandeRefusee;
use App\Mail\ReclamationRecue;
use App\Mail\ReclamationReponse;
use App\Models\Demande;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailExampleController extends Controller
{
    // Example 1: Approve a document request
    public function approuveDemande($demandeId)
    {
        // Get the demande, etudiant, and type
        $demande = Demande::findOrFail($demandeId);
        $etudiant = $demande->etudiant; // Assuming relationship exists
        $typeDocument = $demande->type_document; // e.g., "Attestation de Scolarité"
        
        // Update demande status
        $demande->update([
            'statut' => 'validee',
            'date_validation' => now()
        ]);
        
        // Send email
        Mail::to($etudiant->email)->send(
            new DemandeValidee($demande, $etudiant, $typeDocument)
        );
        
        return response()->json(['message' => 'Demande approuvée et email envoyé']);
    }
    
    // Example 2: Refuse a document request
    public function refuseDemande(Request $request, $demandeId)
    {
        $request->validate([
            'raison_refus' => 'required|string|max:500'
        ]);
        
        $demande = Demande::findOrFail($demandeId);
        $etudiant = $demande->etudiant;
        $typeDocument = $demande->type_document;
        $raisonRefus = $request->raison_refus;
        
        // Update demande status
        $demande->update([
            'statut' => 'refusee',
            'raison_refus' => $raisonRefus,
            'date_refus' => now()
        ]);
        
        // Send email
        Mail::to($etudiant->email)->send(
            new DemandeRefusee($demande, $etudiant, $typeDocument, $raisonRefus)
        );
        
        return response()->json(['message' => 'Demande refusée et email envoyé']);
    }
    
    // Example 3: Student submits a reclamation
    public function submitReclamation(Request $request)
    {
        $request->validate([
            'type_reclamation' => 'required|string',
            'description' => 'required|string|max:1000'
        ]);
        
        // Create reclamation
        $reclamation = Reclamation::create([
            'etudiant_id' => auth()->id(),
            'type' => $request->type_reclamation,
            'description' => $request->description,
            'statut' => 'en_traitement'
        ]);
        
        $etudiant = auth()->user();
        $typeReclamation = $request->type_reclamation; // e.g., "Problème d'inscription"
        
        // Send confirmation email
        Mail::to($etudiant->email)->send(
            new ReclamationRecue($reclamation, $etudiant, $typeReclamation)
        );
        
        return response()->json([
            'message' => 'Réclamation soumise avec succès',
            'reclamation_id' => $reclamation->id
        ]);
    }
    
    // Example 4: Admin responds to a reclamation
    public function repondreReclamation(Request $request, $reclamationId)
    {
        $request->validate([
            'reponse_message' => 'required|string|max:2000',
            'actions_prises' => 'nullable|string|max:500'
        ]);
        
        $reclamation = Reclamation::findOrFail($reclamationId);
        $etudiant = $reclamation->etudiant;
        $typeReclamation = $reclamation->type;
        
        // Update reclamation
        $reclamation->update([
            'reponse_admin' => $request->reponse_message,
            'actions_prises' => $request->actions_prises,
            'date_reponse' => now(),
            'statut' => 'traitee'
        ]);
        
        $adminNom = auth()->user()->nom . ' ' . auth()->user()->prenom;
        
        // Send response email
        Mail::to($etudiant->email)->send(
            new ReclamationReponse(
                $reclamation,
                $etudiant,
                $typeReclamation,
                $request->reponse_message,
                $adminNom,
                $request->actions_prises
            )
        );
        
        return response()->json(['message' => 'Réponse envoyée avec succès']);
    }
    
    // Example 5: Queue emails for better performance (recommended for production)
    public function approveDemandeWithQueue($demandeId)
    {
        $demande = Demande::findOrFail($demandeId);
        $etudiant = $demande->etudiant;
        $typeDocument = $demande->type_document;
        
        $demande->update([
            'statut' => 'validee',
            'date_validation' => now()
        ]);
        
        // Use queue() instead of send() for better performance
        Mail::to($etudiant->email)
            ->queue(new DemandeValidee($demande, $etudiant, $typeDocument));
        
        return response()->json(['message' => 'Demande approuvée, email en cours d\'envoi']);
    }
}
