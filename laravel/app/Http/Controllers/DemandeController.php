<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Etudiant;
use App\Models\AttestationScolaire;
use App\Models\AttestationReussite;
use App\Models\ReleveNotes;
use App\Models\ConventionStage;
use App\Models\Reclamation;
use App\Models\Inscription;
use App\Models\DecisionAnnee;
use App\Models\Professeur;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemandeController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    /**
     * Valider les informations de l'étudiant en temps réel
     */
    public function validateStudent(Request $request)
    {
        // Accept nullable fields to handle empty strings from frontend
        $validated = $request->validate([
            'email' => 'nullable|string',
            'apogee' => 'nullable|string',
            'cin' => 'nullable|string',
        ]);

        // Get values, treating empty strings as null
        $email = !empty($validated['email']) ? $validated['email'] : null;
        $apogee = !empty($validated['apogee']) ? $validated['apogee'] : null;
        $cin = !empty($validated['cin']) ? $validated['cin'] : null;

        // If all fields are empty, return early with all false
        if (!$email && !$apogee && !$cin) {
            return response()->json([
                'email_valid' => false,
                'apogee_valid' => false,
                'cin_valid' => false,
                'student_valid' => false,
                'all_valid' => false,
                'error_type' => 'empty'
            ]);
        }

        // Step 1: Pattern/Format validation (only for filled fields)
        $emailPatternValid = $email ? (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) : false;
        $apogeePatternValid = $apogee ? (bool)preg_match('/^\d{6,10}$/', $apogee) : false;
        $cinPatternValid = $cin ? (bool)preg_match('/^[A-Z]{1,2}\d{4,8}$/i', $cin) : false;

        // If any filled field has invalid pattern, return early
        if (($email && !$emailPatternValid) || ($apogee && !$apogeePatternValid) || ($cin && !$cinPatternValid)) {
            return response()->json([
                'email_valid' => $emailPatternValid,
                'apogee_valid' => $apogeePatternValid,
                'cin_valid' => $cinPatternValid,
                'student_valid' => false,
                'all_valid' => false,
                'error_type' => 'pattern'
            ]);
        }

        // Step 2: Database existence check (only for fields with valid patterns)
        $emailExists = $emailPatternValid ? Etudiant::where('email', $email)->exists() : false;
        $apogeeExists = $apogeePatternValid ? Etudiant::where('apogee', $apogee)->exists() : false;
        $cinExists = $cinPatternValid ? Etudiant::where('cin', $cin)->exists() : false;

        // If any validated field doesn't exist in database, return early
        if (($emailPatternValid && !$emailExists) || ($apogeePatternValid && !$apogeeExists) || ($cinPatternValid && !$cinExists)) {
            return response()->json([
                'email_valid' => $emailExists,
                'apogee_valid' => $apogeeExists,
                'cin_valid' => $cinExists,
                'student_valid' => false,
                'all_valid' => false,
                'error_type' => 'existence'
            ]);
        }

        // Step 3: Relationship check - only if all three fields are filled and valid
        $studentExists = false;
        $anneesUniversitaires = [];
        
        if ($emailPatternValid && $apogeePatternValid && $cinPatternValid) {
            $student = Etudiant::where('email', $email)
                ->where('apogee', $apogee)
                ->where('cin', $cin)
                ->first();
            $studentExists = $student !== null;
            
            // Si l'étudiant existe, récupérer ses années universitaires
            if ($studentExists) {
                $anneesUniversitaires = $student->inscriptions()
                    ->with('anneeUniversitaire')
                    ->get()
                    ->map(function ($inscription) {
                        return [
                            'id' => $inscription->id,
                            'libelle' => $inscription->anneeUniversitaire->libelle ?? 'Année inconnue'
                        ];
                    })
                    ->values()
                    ->toArray();
            }
        }

        return response()->json([
            'email_valid' => $emailExists,
            'apogee_valid' => $apogeeExists,
            'cin_valid' => $cinExists,
            'student_valid' => $studentExists,
            'all_valid' => $studentExists,
            'error_type' => $studentExists ? 'none' : 'relationship',
            'annees_universitaires' => $anneesUniversitaires
        ]);
    }

    /**
     * Créer une nouvelle demande de document
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation des données de base
        $validated = $request->validate([
            'email' => 'required|email',
            'apogee' => 'required|string',
            'cin' => 'required|string',
            'type_document' => 'required|in:attestation_scolaire,attestation_reussite,releve_notes,convention_stage',
        ]);

        // Vérifier que l'étudiant existe avec ces informations
        $etudiant = Etudiant::where('email', $validated['email'])
            ->where('apogee', $validated['apogee'])
            ->where('cin', $validated['cin'])
            ->first();

        if (!$etudiant) {
            return response()->json([
                'success' => false,
                'message' => 'Les informations fournies ne correspondent à aucun étudiant enregistré.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            // Générer un numéro de demande unique
            $numDemande = 'DEM-' . strtoupper(Str::random(8)) . '-' . date('Ymd');

            // Get inscription_id - either from request or find the most recent one
            $inscriptionId = $request->input('inscription_id', null);
            
            if (!$inscriptionId) {
                // Automatically find the student's most recent inscription
                $inscription = Inscription::where('etudiant_id', $etudiant->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($inscription) {
                    $inscriptionId = $inscription->id;
                }
            } else {
                // Verify the inscription belongs to this student
                $inscription = Inscription::where('id', $inscriptionId)
                    ->where('etudiant_id', $etudiant->id)
                    ->first();
                if (!$inscription) {
                    return response()->json([
                        'success' => false,
                        'message' => 'L\'inscription fournie n\'appartient pas à cet étudiant.'
                    ], 400);
                }
            }

            // Créer la demande
            $demande = Demande::create([
                'etudiant_id' => $etudiant->id,
                'inscription_id' => $inscriptionId,
                'type_document' => $validated['type_document'],
                'num_demande' => $numDemande,
                'date_demande' => now(),
                'status' => 'en_attente',
            ]);

            // Créer les détails spécifiques selon le type de document
            $this->createDocumentDetails($demande, $request, $validated['type_document']);

            DB::commit();

            // Envoyer email de confirmation
            $this->emailService->envoyerConfirmationDemande($demande);
            
            /** @var \Carbon\Carbon $dateDemande */
            $dateDemande = $demande->date_demande;
            
            return response()->json([
                'success' => true,
                'message' => 'Votre demande a été enregistrée avec succès.',
                'data' => [
                    'num_demande' => $numDemande,
                    'type_document' => $validated['type_document'],
                    'date_demande' => $dateDemande->format('d/m/Y'),
                    'status' => 'En attente de traitement',
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enregistrement de votre demande.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer les détails spécifiques du document
     */
    private function createDocumentDetails(Demande $demande, Request $request, string $type)
    {
        switch ($type) {
            case 'attestation_scolaire':
                // Attestation scolaire now only needs demande_id (no additional fields)
                AttestationScolaire::create([
                    'demande_id' => $demande->id,
                ]);
                break;

            case 'attestation_reussite':
                // Accept text values from frontend, try to find matching decision_annee_id
                // If inscription exists, link to decision. Otherwise create without decision.
                $decisionAnneeId = null;
                
                if ($demande->inscription_id) {
                    // Try to find a decision for this inscription
                    $decisionAnnee = DecisionAnnee::where('inscription_id', $demande->inscription_id)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    
                    if ($decisionAnnee) {
                        $decisionAnneeId = $decisionAnnee->id;
                    }
                }

                AttestationReussite::create([
                    'demande_id' => $demande->id,
                    'decision_annee_id' => $decisionAnneeId,
                ]);
                break;

            case 'releve_notes':
                // Accept text values from frontend, try to find matching decision_annee_id
                // If inscription exists, link to decision. Otherwise create without decision.
                $decisionAnneeId = null;
                
                if ($demande->inscription_id) {
                    // Try to find a decision for this inscription
                    $decisionAnnee = DecisionAnnee::where('inscription_id', $demande->inscription_id)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    
                    if ($decisionAnnee) {
                        $decisionAnneeId = $decisionAnnee->id;
                    }
                }

                ReleveNotes::create([
                    'demande_id' => $demande->id,
                    'decision_annee_id' => $decisionAnneeId,
                ]);
                break;

            case 'convention_stage':
                $validated = $request->validate([
                    'date_debut' => 'required|date',
                    'date_fin' => 'required|date|after:date_debut',
                    'entreprise' => 'required|string',
                    'adresse_entreprise' => 'required|string',
                    'email_encadrant' => 'required|email',
                    'telephone_encadrant' => 'required|string',
                    'encadrant_entreprise' => 'required|string',
                    'encadrant_pedagogique_id' => 'nullable|exists:professeurs,id',
                    'fonction_encadrant' => 'required|string',
                    'sujet' => 'required|string',
                ]);
                
                ConventionStage::create([
                    'demande_id' => $demande->id,
                    'date_debut' => $validated['date_debut'],
                    'date_fin' => $validated['date_fin'],
                    'entreprise' => $validated['entreprise'],
                    'adresse_entreprise' => $validated['adresse_entreprise'],
                    'email_encadrant' => $validated['email_encadrant'],
                    'telephone_encadrant' => $validated['telephone_encadrant'],
                    'encadrant_entreprise' => $validated['encadrant_entreprise'],
                    'encadrant_pedagogique_id' => $validated['encadrant_pedagogique_id'] ?? null,
                    'fonction_encadrant' => $validated['fonction_encadrant'],
                    'sujet' => $validated['sujet'],
                ]);
                break;
        }
    }

    /**
     * Créer une nouvelle réclamation
     */
    public function createReclamation(Request $request)
    {
        $validated = $request->validate([
            'num_demande' => 'required|string',
            'type' => 'required|in:retard,refus_injustifie,document_incorrect,probleme_technique',
            'description' => 'required|string|min:10',
            'piece_jointe' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        try {
            // Trouver la demande correspondante
            $demande = Demande::where('num_demande', $validated['num_demande'])->first();
            
            if (!$demande) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le numéro de demande fourni est invalide.'
                ], 404);
            }

            // Créer la réclamation
            $reclamation = Reclamation::create([
                'demande_id' => $demande->id,
                'etudiant_id' => $demande->etudiant_id,
                'type' => $validated['type'],
                'description' => $validated['description'],
                'piece_jointe_path' => $request->hasFile('piece_jointe') ? 
                    $request->file('piece_jointe')->store('reclamations', 'public') : null,
            ]);

            // Envoyer email de confirmation de réclamation
            $this->emailService->envoyerConfirmationReclamation($reclamation);

            return response()->json([
                'success' => true,
                'message' => 'Votre réclamation a été enregistrée avec succès.',
                'data' => [
                    'reclamation_id' => $reclamation->id,
                    'num_demande' => $validated['num_demande'],
                    'status' => 'Non traitée',
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enregistrement de votre réclamation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Suivi des demandes d'un étudiant
     */
    public function suiviDemandes(Request $request)
    {
        $validated = $request->validate([
            'email' => 'nullable|email',
            'apogee' => 'nullable|string',
            'cin' => 'nullable|string',
            'num_demande' => 'nullable|string',
        ]);

        // Si un numéro de demande est fourni, rechercher directement
        if (!empty($validated['num_demande'])) {
            $demande = Demande::with([
                'attestationScolaire',
                'attestationReussite.decisionAnnee.inscription.filiere',
                'attestationReussite.decisionAnnee.inscription.niveau',
                'attestationReussite.decisionAnnee.inscription.anneeUniversitaire',
                'releveNotes.decisionAnnee.inscription.filiere',
                'releveNotes.decisionAnnee.inscription.anneeUniversitaire',
                'conventionStage.encadrantPedagogique',
                'inscription.filiere',
                'inscription.niveau',
                'inscription.anneeUniversitaire',
                'etudiant'
            ])
            ->where('num_demande', 'like', '%' . $validated['num_demande'] . '%')
            ->orderBy('created_at', 'desc')
            ->get();

            return response()->json([
                'success' => true,
                'data' => $demande
            ]);
        }

        // Sinon, vérifier l'étudiant et retourner toutes ses demandes
        if (empty($validated['email']) || empty($validated['apogee']) || empty($validated['cin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez fournir un numéro de demande ou vos informations complètes (email, Apogée, CIN).'
            ], 422);
        }

        // Vérifier que l'étudiant existe
        $etudiant = Etudiant::where('email', $validated['email'])
            ->where('apogee', $validated['apogee'])
            ->where('cin', $validated['cin'])
            ->first();

        if (!$etudiant) {
            return response()->json([
                'success' => false,
                'message' => 'Les informations fournies ne correspondent à aucun étudiant enregistré.'
            ], 404);
        }

        // Récupérer toutes les demandes de l'étudiant avec leurs relations
        $demandes = Demande::with([
            'attestationScolaire',
            'attestationReussite.decisionAnnee.inscription.filiere',
            'attestationReussite.decisionAnnee.inscription.niveau',
            'attestationReussite.decisionAnnee.inscription.anneeUniversitaire',
            'releveNotes.decisionAnnee.inscription.filiere',
            'releveNotes.decisionAnnee.inscription.anneeUniversitaire',
            'conventionStage.encadrantPedagogique',
            'inscription.filiere',
            'inscription.niveau',
            'inscription.anneeUniversitaire',
        ])
            ->where('etudiant_id', $etudiant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Transformer les données pour le frontend
        $demandesTransformees = $demandes->map(function ($demande) use ($etudiant) {
            $details = [];
            
            switch ($demande->type_document) {
                case 'attestation_scolaire':
                    if ($demande->attestationScolaire && $demande->inscription) {
                        // Get details from inscription relationship
                        $inscription = $demande->inscription;
                        $details = [
                            'niveau' => $inscription->niveau->libelle ?? null,
                            'filiere' => $inscription->filiere->nom_filiere ?? null,
                            'annee_universitaire' => $inscription->anneeUniversitaire->libelle ?? null,
                        ];
                    }
                    break;
                case 'attestation_reussite':
                    if ($demande->attestationReussite && $demande->attestationReussite->decisionAnnee) {
                        $decision = $demande->attestationReussite->decisionAnnee;
                        $inscription = $decision->inscription;
                        $details = [
                            'filiere' => $inscription->filiere->nom_filiere ?? null,
                            'annee_universitaire' => $inscription->anneeUniversitaire->libelle ?? null,
                            'decision' => $decision->decision,
                            'mention' => $decision->mention,
                            'moyenne' => $decision->moyenne_annuelle,
                        ];
                    }
                    break;
                case 'releve_notes':
                    if ($demande->releveNotes && $demande->releveNotes->decisionAnnee) {
                        $decision = $demande->releveNotes->decisionAnnee;
                        $inscription = $decision->inscription;
                        
                        // Get notes with modules
                        $notes = $inscription->notes()->with('moduleNiveau.module')->get()->map(function($note) {
                            return [
                                'module' => $note->moduleNiveau->module->nom_module ?? 'Module inconnu',
                                'note' => $note->note,
                                'resultat' => $note->est_valide ? 'Validé' : 'Non Validé',
                            ];
                        });

                        $details = [
                            'annee_universitaire' => $inscription->anneeUniversitaire->libelle ?? null,
                            'niveau' => $inscription->niveau->libelle ?? null,
                            'filiere' => $inscription->filiere->nom_filiere ?? null,
                            'decision' => $decision->decision,
                            'moyenne' => $decision->moyenne_annuelle,
                            'mention' => $decision->mention,
                            'session' => $decision->type_session,
                            'resultats' => $notes
                        ];
                    }
                    break;
                case 'convention_stage':
                    if ($demande->conventionStage) {
                        $details = [
                            'entreprise' => $demande->conventionStage->entreprise,
                            'date_debut' => $demande->conventionStage->date_debut?->format('Y-m-d'),
                            'date_fin' => $demande->conventionStage->date_fin?->format('Y-m-d'),
                            'encadrant_pedagogique' => $demande->conventionStage->encadrantPedagogique 
                                ? $demande->conventionStage->encadrantPedagogique->nom . ' ' . $demande->conventionStage->encadrantPedagogique->prenom
                                : null,
                        ];
                    }
                    break;
            }

            /** @var \Carbon\Carbon $dateDemande */
            $dateDemande = $demande->date_demande;

            return [
                'id' => $demande->id,
                'num_demande' => $demande->num_demande,
                'type_document' => $demande->type_document,
                'status' => $demande->status,
                'date_demande' => $dateDemande->format('d/m/Y'),
                'raison_refus' => $demande->raison_refus,
                'details' => $details,
                'etudiant' => [
                    'nom' => $etudiant->nom,
                    'prenom' => $etudiant->prenom,
                    'email' => $etudiant->email,
                    'apogee' => $etudiant->apogee,
                    'cin' => $etudiant->cin,
                    'date_naissance' => $etudiant->date_naissance, // formatted in frontend or keep as is
                    'lieu_naissance' => $etudiant->lieu_naissance,
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $demandesTransformees
        ]);
    }

    /**
     * Télécharger le PDF d'une demande validée
     */
    public function downloadPdf($num_demande)
    {
        $demande = Demande::where('num_demande', $num_demande)
            ->where('status', 'validee')
            ->first();

        if (!$demande) {
            return response()->json([
                'success' => false,
                'message' => 'Demande non trouvée ou non validée.'
            ], 404);
        }

        $pdfService = app(\App\Services\PdfService::class);
        
        try {
            $pdfPath = null;
            
            switch ($demande->type_document) {
                case 'releve_notes':
                    $pdfPath = $pdfService->genererReleveNotes($demande);
                    $filename = 'Releve_Notes.pdf';
                    break;
                case 'attestation_scolaire':
                    $pdfPath = $pdfService->genererAttestationScolaire($demande);
                    $filename = 'Attestation_Scolarite.pdf';
                    break;
                case 'attestation_reussite':
                    $pdfPath = $pdfService->genererAttestationReussite($demande);
                    $filename = 'Attestation_Reussite.pdf';
                    break;
                case 'convention_stage':
                    $pdfPath = $pdfService->genererConventionStage($demande);
                    $filename = 'Convention_Stage.pdf';
                    break;
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Type de document non supporté.'
                    ], 400);
            }

            return response()->download(
                storage_path('app/' . $pdfPath),
                $filename,
                ['Content-Type' => 'application/pdf']
            );
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
