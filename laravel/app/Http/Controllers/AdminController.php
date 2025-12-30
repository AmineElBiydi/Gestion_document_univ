<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Admin;
use App\Models\Reclamation;
use App\Models\Etudiant;
use App\Mail\ReclamationReponse;
use App\Mail\DemandeValidee;
use Illuminate\Support\Facades\Mail;
use App\Services\EmailService;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    protected $emailService;
    protected $pdfService;

    public function __construct(EmailService $emailService, PDFService $pdfService)
    {
        $this->emailService = $emailService;
        $this->pdfService = $pdfService;
    }
    /**
     * Authentification de l'administrateur
     */
    public function login(Request $request)
    {
        $request->validate([
            'identifiant' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('identifiant', $request->identifiant)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiant ou mot de passe incorrect.'
            ], 401);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie.',
            'data' => [
                'admin' => [
                    'id' => $admin->id,
                    'identifiant' => $admin->identifiant,
                    'nom' => $admin->nom,
                    'prenom' => $admin->prenom,
                    'email' => $admin->email,
                    'role' => $admin->role,
                ],
                'token' => $token,
            ]
        ]);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie.'
        ]);
    }

    /**
     * Tableau de bord - Statistiques
     */
    public function dashboard()
    {
        // Current month stats
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth()->month;
        $lastMonthYear = now()->subMonth()->year;
        
        // Get current month counts
        $currentTotal = Demande::whereMonth('created_at', $currentMonth)
                              ->whereYear('created_at', $currentYear)
                              ->count();
        $currentEnAttente = Demande::where('status', 'en_attente')
                                   ->whereMonth('created_at', $currentMonth)
                                   ->whereYear('created_at', $currentYear)
                                   ->count();
        $currentValidees = Demande::where('status', 'validee')
                                  ->whereMonth('created_at', $currentMonth)
                                  ->whereYear('created_at', $currentYear)
                                  ->count();
        $currentRejetees = Demande::where('status', 'rejetee')
                                  ->whereMonth('created_at', $currentMonth)
                                  ->whereYear('created_at', $currentYear)
                                  ->count();
        
        // Get last month counts
        $lastTotal = Demande::whereMonth('created_at', $lastMonth)
                           ->whereYear('created_at', $lastMonthYear)
                           ->count();
        $lastEnAttente = Demande::where('status', 'en_attente')
                               ->whereMonth('created_at', $lastMonth)
                               ->whereYear('created_at', $lastMonthYear)
                               ->count();
        $lastValidees = Demande::where('status', 'validee')
                               ->whereMonth('created_at', $lastMonth)
                               ->whereYear('created_at', $lastMonthYear)
                               ->count();
        $lastRejetees = Demande::where('status', 'rejetee')
                               ->whereMonth('created_at', $lastMonth)
                               ->whereYear('created_at', $lastMonthYear)
                               ->count();
        
        // Calculate percentage changes
        $totalChange = $lastTotal > 0 ? (($currentTotal - $lastTotal) / $lastTotal) * 100 : 0;
        $enAttenteChange = $lastEnAttente > 0 ? (($currentEnAttente - $lastEnAttente) / $lastEnAttente) * 100 : 0;
        $valideesChange = $lastValidees > 0 ? (($currentValidees - $lastValidees) / $lastValidees) * 100 : 0;
        $rejeteesChange = $lastRejetees > 0 ? (($currentRejetees - $lastRejetees) / $lastRejetees) * 100 : 0;
        
        $stats = [
            'total_demandes' => [
                'value' => Demande::count(),
                'change' => round($totalChange, 1),
                'trend' => $totalChange >= 0 ? 'up' : 'down'
            ],
            'en_attente' => [
                'value' => Demande::where('status', 'en_attente')->count(),
                'change' => round($enAttenteChange, 1),
                'trend' => $enAttenteChange >= 0 ? 'up' : 'down'
            ],
            'validees' => [
                'value' => Demande::where('status', 'validee')->count(),
                'change' => round($valideesChange, 1),
                'trend' => $valideesChange >= 0 ? 'up' : 'down'
            ],
            'rejetees' => [
                'value' => Demande::where('status', 'rejetee')->count(),
                'change' => round($rejeteesChange, 1),
                'trend' => $rejeteesChange >= 0 ? 'up' : 'down'
            ],
            'reclamations' => [
                'value' => \App\Models\Reclamation::count(),
                'change' => 0, // TODO: Calculate month-over-month change for reclamations
                'trend' => 'down' // TODO: Calculate trend for reclamations
            ],
            'par_type' => [
                'attestation_scolaire' => Demande::where('type_document', 'attestation_scolaire')->count(),
                'attestation_reussite' => Demande::where('type_document', 'attestation_reussite')->count(),
                'releve_notes' => Demande::where('type_document', 'releve_notes')->count(),
                'convention_stage' => Demande::where('type_document', 'convention_stage')->count(),
            ],
            'par_semaine' => [
                ['name' => 'Lun', 'demandes' => $this->getDemandesByDayOfWeek(1)],
                ['name' => 'Mar', 'demandes' => $this->getDemandesByDayOfWeek(2)],
                ['name' => 'Mer', 'demandes' => $this->getDemandesByDayOfWeek(3)],
                ['name' => 'Jeu', 'demandes' => $this->getDemandesByDayOfWeek(4)],
                ['name' => 'Ven', 'demandes' => $this->getDemandesByDayOfWeek(5)],
                ['name' => 'Sam', 'demandes' => $this->getDemandesByDayOfWeek(6)],
                ['name' => 'Dim', 'demandes' => $this->getDemandesByDayOfWeek(0)],
            ],
            'performance_mensuelle' => [
                ['name' => 'Sem 1', 'traitees' => $this->getProcessedDemandesByWeek(weekNumber: 1), 'recues' => $this->getReceivedDemandesByWeek(1)],
                ['name' => 'Sem 2', 'traitees' => $this->getProcessedDemandesByWeek(2), 'recues' => $this->getReceivedDemandesByWeek(2)],
                ['name' => 'Sem 3', 'traitees' => $this->getProcessedDemandesByWeek(3), 'recues' => $this->getReceivedDemandesByWeek(3)],
                ['name' => 'Sem 4', 'traitees' => $this->getProcessedDemandesByWeek(4), 'recues' => $this->getReceivedDemandesByWeek(4)],
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get demandes by day of week for current week
     */
    private function getDemandesByDayOfWeek($dayOfWeek)
    {
        $startOfWeek = now()->startOfWeek();
        $targetDay = $startOfWeek->copy()->addDays($dayOfWeek === 0 ? 6 : $dayOfWeek - 1);
        
        return Demande::whereDate('created_at', $targetDay->format('Y-m-d'))->count();
    }

    /**
     * Get processed demandes by week for current month
     */
    private function getProcessedDemandesByWeek($weekNumber)
    {
        $startOfMonth = now()->startOfMonth();
        $startOfWeek = $startOfMonth->copy()->addWeeks($weekNumber - 1)->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->endOfWeek();
        
        // Ensure we don't go beyond current month
        if ($endOfWeek > now()->endOfMonth()) {
            $endOfWeek = now()->endOfMonth();
        }
        
        return Demande::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['validee', 'rejetee'])
            ->count();
    }

    /**
     * Get received demandes by week for current month
     */
    private function getReceivedDemandesByWeek($weekNumber)
    {
        $startOfMonth = now()->startOfMonth();
        $startOfWeek = $startOfMonth->copy()->addWeeks($weekNumber - 1)->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->endOfWeek();
        
        // Ensure we don't go beyond current month
        if ($endOfWeek > now()->endOfMonth()) {
            $endOfWeek = now()->endOfMonth();
        }
        
        return Demande::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
    }

    /**
     * Liste des demandes avec filtres
     */
    public function getDemandes(Request $request)
    {
        $query = Demande::with([
            'etudiant',
            'inscription.filiere',
            'inscription.niveau',
            'inscription.anneeUniversitaire',
            'attestationScolaire',
            'attestationReussite.decisionAnnee.inscription.filiere',
            'attestationReussite.decisionAnnee.inscription.niveau',
            'attestationReussite.decisionAnnee.inscription.anneeUniversitaire',
            'releveNotes.decisionAnnee.inscription.filiere',
            'releveNotes.decisionAnnee.inscription.anneeUniversitaire',
            'conventionStage.encadrantPedagogique',
            'traiteParAdmin',
        ]);

        // Filtres
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('type_document') && $request->type_document !== 'all') {
            $query->where('type_document', $request->type_document);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('etudiant', function ($q) use ($search) {
                $q->where('apogee', 'like', "%{$search}%")
                  ->orWhere('cin', 'like', "%{$search}%")
                  ->orWhere('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%");
            });
        }

        if ($request->has('date_debut') && $request->has('date_fin')) {
            $query->whereBetween('date_demande', [$request->date_debut, $request->date_fin]);
        }

        $demandes = $query->orderBy('created_at', direction: 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $demandes
        ]);
    }

    /**
     * Valider une demande
     */
    public function validerDemande(Request $request, $id)
    {
        $demande = Demande::with([
            'etudiant',
            'inscription.filiere',
            'inscription.niveau',
            'inscription.anneeUniversitaire',
            'attestationScolaire',
            'attestationReussite.decisionAnnee',
            'releveNotes.decisionAnnee',
            'conventionStage'
        ])->findOrFail($id);

        \DB::beginTransaction();

        try {
            // Update demande status
            $demande->status = 'validee';
            $demande->date_traitement = now();
            $demande->traite_par_admin_id = auth()->id();
            $demande->save();

            // Generate PDF
            $pdfPath = null;
            try {
                $pdfPath = $this->pdfService->generatePDF($demande);
                $demande->fichier_genere_path = $pdfPath;
                $demande->save();
                
                // Update attestation_scolaires table with generation timestamp
                if ($demande->type_document === 'attestation_scolaire' && $demande->attestationScolaire) {
                    $demande->attestationScolaire->update([
                        'updated_at' => now()
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('PDF generation failed: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                
                // Still send email even if PDF generation fails
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error validating demande: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la validation: ' . $e->getMessage()
            ], 500);
        }

        // Send email notification to student with PDF attachment
        // Done after commit to avoid holding lock during email sending
        $etudiant = $demande->etudiant;
        $typeDocument = $demande->getTypeDocumentLabel();
        
        try {
            Mail::to($etudiant->email)->send(
                new DemandeValidee($demande, $etudiant, $typeDocument, $pdfPath)
            );
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Demande validée avec succès.',
            'data' => $demande->fresh()->load('etudiant'),
            'pdf_path' => $pdfPath
        ]);
    }

    /**
     * Preview PDF before validation
     */
    public function previewPDF($id)
    {
        $demande = Demande::with([
            'etudiant',
            'inscription.filiere',
            'inscription.niveau',
            'inscription.anneeUniversitaire',
            'attestationScolaire',
            'attestationReussite.decisionAnnee',
            'releveNotes.decisionAnnee',
            'conventionStage'
        ])->findOrFail($id);
        
        if ($demande->status !== 'en_attente') {
            return response()->json([
                'success' => false,
                'message' => 'Seules les demandes en attente peuvent être prévisualisées.'
            ], 400);
        }

        try {
            $pdfPath = $this->pdfService->generatePDF($demande);
            
            // Convert absolute path to relative URL
            $relativePath = str_replace(storage_path('app/public/'), '', $pdfPath);
            $pdfUrl = url('storage/' . $relativePath);
            
            return response()->json([
                'success' => true,
                'pdf_url' => $pdfUrl,
                'pdf_path' => $pdfPath
            ]);
        } catch (\Exception $e) {
            \Log::error('PDF preview generation failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refuser une demande
     */
    public function refuserDemande(Request $request, $id)
    {
        $request->validate([
            'raison' => 'required|string',
        ]);

        $demande = Demande::findOrFail($id);

        $demande->status = 'rejetee';
        $demande->raison_refus = $request->raison;
        $demande->date_traitement = now();
        $demande->traite_par_admin_id = auth()->id();
        $demande->save();

        // Envoyer email de refus à l'étudiant
        try {
            $this->emailService->envoyerRefusDemande($demande);
        } catch (\Exception $e) {
            \Log::error('Email refus sending failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Demande refusée avec succès.',
            'data' => $demande->load('etudiant')
        ]);
    }

    /**
     * Obtenir les détails d'une demande
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDemandeDetails($id)
    {
        $demande = Demande::with([
            'etudiant',
            'inscription.filiere',
            'inscription.niveau',
            'inscription.anneeUniversitaire',
            'attestationScolaire',
            'attestationReussite.decisionAnnee.inscription.filiere',
            'attestationReussite.decisionAnnee.inscription.niveau',
            'attestationReussite.decisionAnnee.inscription.anneeUniversitaire',
            'releveNotes.decisionAnnee.inscription.filiere',
            'releveNotes.decisionAnnee.inscription.anneeUniversitaire',
            'conventionStage.encadrantPedagogique',
            'traiteParAdmin',
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $demande
        ]);
    }

    /**
     * Liste des réclamations avec filtres
     */
    public function getReclamations(Request $request)
    {
        $query = Reclamation::with(['etudiant', 'demande']);

        // Filtres
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('etudiant', function ($q) use ($search) {
                $q->where('apogee', 'like', "%{$search}%")
                  ->orWhere('cin', 'like', "%{$search}%")
                  ->orWhere('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%");
            })->orWhereHas('demande', function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%");
            });
        }

        $reclamations = $query->with(['traiteParAdmin'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $reclamations
        ]);
    }

    /**
     * Répondre à une réclamation
     */
    public function repondreReclamation(Request $request, $id)
    {
        // Debug: Log request data
        \Log::info('RepondreReclamation request data:', $request->all());
        
        try {
            $validated = $request->validate([
                'reponse' => 'required|string|min:10'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation errors:', $e->errors());
            return response()->json([
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ], 422);
        }

        $reclamation = Reclamation::findOrFail($id);

        $reclamation->update([
            'status' => 'traitee',
            'reponse' => $validated['reponse'],
            'date_traitement' => now(),
            'traite_par_admin_id' => auth()->id(),
        ]);

        // Send email notification to student
        $etudiant = $reclamation->etudiant;
        $typeReclamation = $reclamation->type;
        $adminNom = auth()->user()->nom . ' ' . auth()->user()->prenom;
        
        Mail::to($etudiant->email)->send(
            new ReclamationReponse(
                $reclamation,
                $etudiant,
                $typeReclamation,
                $validated['reponse'],
                $adminNom
            )
        );

        return response()->json([
            'success' => true,
            'message' => 'Réponse envoyée avec succès',
            'data' => $reclamation
        ]);
    }

    /**
     * Obtenir l'historique des demandes finalisées (acceptées + refusées)
     */
    public function getHistorique(Request $request)
    {
        $query = Demande::with([
            'etudiant',
            'inscription.filiere',
            'inscription.niveau',
            'inscription.anneeUniversitaire',
            'attestationScolaire',
            'attestationReussite.decisionAnnee.inscription.filiere',
            'attestationReussite.decisionAnnee.inscription.niveau',
            'attestationReussite.decisionAnnee.inscription.anneeUniversitaire',
            'releveNotes.decisionAnnee.inscription.filiere',
            'releveNotes.decisionAnnee.inscription.anneeUniversitaire',
            'conventionStage.encadrantPedagogique',
            'traiteParAdmin',
        ])
            ->whereIn('status', ['validee', 'rejetee']);

        // Filtres
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('type_document') && $request->type_document !== 'all') {
            $query->where('type_document', $request->type_document);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('etudiant', function ($q) use ($search) {
                $q->where('apogee', 'like', "%{$search}%")
                  ->orWhere('cin', 'like', "%{$search}%")
                  ->orWhere('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%");
            });
        }

        if ($request->has('date_debut') && $request->has('date_fin')) {
            $query->whereBetween('date_demande', [$request->date_debut, $request->date_fin]);
        }

        $demandes = $query->orderBy('updated_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $demandes
        ]);
    }

    /**
     * Obtenir les demandes en attente uniquement
     */
    public function getDemandesAttente(Request $request)
    {
        $query = Demande::with([
            'etudiant',
            'inscription.filiere',
            'inscription.niveau',
            'inscription.anneeUniversitaire',
            'attestationScolaire',
            'attestationReussite.decisionAnnee.inscription.filiere',
            'attestationReussite.decisionAnnee.inscription.niveau',
            'attestationReussite.decisionAnnee.inscription.anneeUniversitaire',
            'releveNotes.decisionAnnee.inscription.filiere',
            'releveNotes.decisionAnnee.inscription.anneeUniversitaire',
            'conventionStage.encadrantPedagogique',
        ])
            ->where('status', 'en_attente');

        // Filtres
        if ($request->has('type_document') && $request->type_document !== 'all') {
            $query->where('type_document', $request->type_document);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('etudiant', function ($q) use ($search) {
                $q->where('apogee', 'like', "%{$search}%")
                  ->orWhere('cin', 'like', "%{$search}%")
                  ->orWhere('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%");
            });
        }

        $demandes = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $demandes
        ]);
    }

    /**
     * Inverser le statut d'une demande (rejetée -> acceptée)
     */
    public function reverserDemande(Request $request, $id)
    {
        $demande = Demande::findOrFail($id);

        // Vérifier que la demande est bien refusée
        if ($demande->status !== 'rejetee') {
            return response()->json([
                'success' => false,
                'message' => 'Seules les demandes refusées peuvent être inversées'
            ], 400);
        }

        $demande->update([
            'status' => 'validee',
            'raison_refus' => null, // Effacer la raison de refus
            'date_traitement' => now(),
            'traite_par_admin_id' => auth()->id(),
        ]);

        // Send email notification to student
        $etudiant = $demande->etudiant;
        $typeDocument = $demande->getTypeDocumentLabel();
        
        Mail::to($etudiant->email)->send(
            new DemandeValidee($demande, $etudiant, $typeDocument)
        );

        return response()->json([
            'success' => true,
            'message' => 'Demande inversée avec succès',
            'data' => $demande
        ]);
    }
}
