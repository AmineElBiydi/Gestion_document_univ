<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Etudiant;
use App\Models\Inscription;
use App\Models\DecisionAnnee;
use App\Models\Note;
use App\Models\Demande;

class CheckDatabaseState extends Command
{
    protected $signature = 'db:check-state';
    protected $description = 'Vérifier l\'état de la base de données pour les tests PDF';

    public function handle()
    {
        $this->info('📊 STATISTIQUES DE LA BASE:');
        $this->line(str_repeat("-", 50));
        
        $this->line("Étudiants: " . Etudiant::count());
        $this->line("Inscriptions: " . Inscription::count());
        $this->line("Décisions: " . DecisionAnnee::count());
        $this->line("Notes: " . Note::count());
        $this->line("Demandes: " . Demande::count());
        $this->newLine();

        // Trouver un étudiant avec décision
        $etudiantAvecDecision = Etudiant::whereHas('inscriptions.decisionsAnnee')->first();

        if ($etudiantAvecDecision) {
            $this->info("✅ ÉTUDIANT AVEC DÉCISION TROUVÉ:");
            $this->line(str_repeat("-", 50));
            $this->line("Nom: {$etudiantAvecDecision->nom} {$etudiantAvecDecision->prenom}");
            $this->line("Email: {$etudiantAvecDecision->email}");
            $this->line("Apogée: {$etudiantAvecDecision->apogee}");
            $this->line("CIN: {$etudiantAvecDecision->cin}");
            
            $inscription = $etudiantAvecDecision->inscriptions()->with('decisionsAnnee')->first();
            if ($inscription && $inscription->decisionsAnnee->isNotEmpty()) {
                $decision = $inscription->decisionsAnnee->first();
                $this->line("Moyenne: {$decision->moyenne_annuelle}/20");
                $this->line("Mention: {$decision->mention}");
                $this->line("Décision: {$decision->decision}");
                
                $notesCount = Note::where('inscription_id', $inscription->id)->count();
                $this->line("Nombre de notes: {$notesCount}");
            }
        } else {
            $this->error("❌ AUCUN ÉTUDIANT AVEC DÉCISION");
            $this->warn("Exécutez: php artisan db:seed --class=CompleteStudentDataSeeder");
        }

        $this->newLine();

        // Vérifier les demandes existantes
        $demandesReleveNotes = Demande::where('type_document', 'releve_notes')
            ->with('etudiant', 'releveNotes.decisionAnnee')
            ->get();
            
        $this->info("📋 DEMANDES DE RELEVÉ DE NOTES:");
        $this->line(str_repeat("-", 50));

        if ($demandesReleveNotes->isEmpty()) {
            $this->warn("Aucune demande de relevé de notes trouvée.");
            $this->line("Créez-en une depuis le frontend avec les identifiants ci-dessus.");
        } else {
            foreach ($demandesReleveNotes as $demande) {
                $this->newLine();
                $this->line("Demande: {$demande->num_demande}");
                $this->line("Status: {$demande->status}");
                $this->line("Étudiant: {$demande->etudiant->nom} {$demande->etudiant->prenom}");
                
                if ($demande->releveNotes && $demande->releveNotes->decisionAnnee) {
                    $this->info("✅ Décision liée - PDF peut être généré");
                } else {
                    $this->error("❌ Pas de décision - PDF ne peut pas être généré");
                }
            }
        }

        $this->newLine();
        $this->line(str_repeat("=", 50));
        $this->info("🎯 POUR TESTER L'ENVOI DE PDF:");
        $this->line("1. Utilisez les identifiants de l'étudiant ci-dessus");
        $this->line("2. Créez une demande de relevé de notes depuis le frontend");
        $this->line("3. Validez la demande depuis l'interface admin");
        $this->line("4. L'email avec PDF sera envoyé automatiquement");

        return 0;
    }
}
