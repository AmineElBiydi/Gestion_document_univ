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
        $validated = $request->validate([
            'email' => 'required|email',
            'apogee' => 'required|string',
            'cin' => 'required|string',
        ]);

        // Step 1: Pattern/Format validation
        $emailPatternValid = filter_var($validated['email'], FILTER_VALIDATE_EMAIL) !== false;
        $apogeePatternValid = preg_match('/^\d{6,10}$/', $validated['apogee']);
        $cinPatternValid = preg_match('/^[A-Z]{1,2}\d{4,8}$/i', $validated['cin']);

        // If patterns are invalid, return early
        if (!$emailPatternValid || !$apogeePatternValid || !$cinPatternValid) {
            return response()->json([
                'email_valid' => $emailPatternValid,
                'apogee_valid' => $apogeePatternValid,
                'cin_valid' => $cinPatternValid,
                'student_valid' => false,
                'all_valid' => false,
                'error_type' => 'pattern'
            ]);
        }

        // Step 2: Database existence check
        $emailExists = Etudiant::where('email', $validated['email'])->exists();
        $apogeeExists = Etudiant::where('apogee', $validated['apogee'])->exists();
        $cinExists = Etudiant::where('cin', $validated['cin'])->exists();

        // If any field doesn't exist in database, return early
        if (!$emailExists || !$apogeeExists || !$cinExists) {
            return response()->json([
                'email_valid' => $emailExists,
                'apogee_valid' => $apogeeExists,
                'cin_valid' => $cinExists,
                'student_valid' => false,
                'all_valid' => false,
                'error_type' => 'existence'
            ]);
        }

        // Step 3: Relationship check - ensure all three fields belong to same student
        $student = Etudiant::where('email', $validated['email'])
            ->where('apogee', $validated['apogee'])
            ->where('cin', $validated['cin'])
            ->first();

        $studentExists = $student !== null;

        return response()->json([
            'email_valid' => $emailExists,
            'apogee_valid' => $apogeeExists,
            'cin_valid' => $cinExists,
            'student_valid' => $studentExists,
            'all_valid' => $studentExists,
            'error_type' => $studentExists ? 'none' : 'relationship'
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

            // Optionally get inscription_id if provided
            $inscriptionId = $request->input('inscription_id', null);
            if ($inscriptionId) {
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
                // Requires decision_annee_id
                $validated = $request->validate([
                    'decision_annee_id' => 'required|exists:decisions_annee,id',
                ]);
                
                // Verify the decision_annee belongs to the student's inscription if provided
                if ($demande->inscription_id) {
                    $decisionAnnee = DecisionAnnee::findOrFail($validated['decision_annee_id']);
                    if ($decisionAnnee->inscription_id !== $demande->inscription_id) {
                        throw new \Exception('La décision d\'année ne correspond pas à l\'inscription de la demande.');
                    }
                }
                
                AttestationReussite::create([
                    'demande_id' => $demande->id,
                    'decision_annee_id' => $validated['decision_annee_id'],
                ]);
                break;

            case 'releve_notes':
                // Requires decision_annee_id
                $validated = $request->validate([
                    'decision_annee_id' => 'required|exists:decisions_annee,id',
                ]);
                
                // Verify the decision_annee belongs to the student's inscription if provided
                if ($demande->inscription_id) {
                    $decisionAnnee = DecisionAnnee::findOrFail($validated['decision_annee_id']);
                    if ($decisionAnnee->inscription_id !== $demande->inscription_id) {
                        throw new \Exception('La décision d\'année ne correspond pas à l\'inscription de la demande.');
                    }
                }
                
                ReleveNotes::create([
                    'demande_id' => $demande->id,
                    'decision_annee_id' => $validated['decision_annee_id'],
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
            'email' => 'required|email',
            'apogee' => 'required|string',
            'cin' => 'required|string',
        ]);

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
                        $details = [
                            'annee_universitaire' => $inscription->anneeUniversitaire->libelle ?? null,
                            'decision' => $decision->decision,
                            'moyenne' => $decision->moyenne_annuelle,
                            'session' => $decision->type_session,
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
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $demandesTransformees
        ]);
    }
}
