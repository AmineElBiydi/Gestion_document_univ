<?php

namespace Database\Seeders;

use App\Models\Etudiant;
use App\Models\Inscription;
use App\Models\AnneeUniversitaire;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\ModuleNiveau;
use App\Models\Note;
use App\Models\DecisionAnnee;
use Illuminate\Database\Seeder;

class ExistingUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Generates inscriptions and notes for ALL existing students who don't have them.
     */
    public function run(): void
    {
        $etudiants = Etudiant::all();
        $annee = AnneeUniversitaire::where('est_active', true)->first();
        
        if (!$annee) {
            $this->command->error("No active academic year found! Please run AnneeUniversitaireSeeder first.");
            return;
        }

        foreach ($etudiants as $etudiant) {
            $this->command->info("Processing student: {$etudiant->nom} {$etudiant->prenom}");

            // 1. Ensure Inscription
            // We'll arbitrarily assign them to 'Génie Informatique' -> 'CP1' if they lack an inscription
            $inscription = Inscription::where('etudiant_id', $etudiant->id)
                ->where('annee_id', $annee->id)
                ->first();

            if (!$inscription) {
                $filiere = Filiere::first(); // Just pick first one (CP)
                $niveau = Niveau::where('filiere_id', $filiere->id)->first(); // CP1

                $inscription = Inscription::create([
                    'etudiant_id' => $etudiant->id,
                    'annee_id' => $annee->id,
                    'filiere_id' => $filiere->id,
                    'niveau_id' => $niveau->id,
                    'date_inscription' => now()->subMonths(3),
                    'statut' => 'inscrit',
                ]);
                $this->command->info(" - Created Inscription for CP1");
            } else {
                $this->command->info(" - Found Existing Inscription");
            }

            // 2. Generate Notes for this Inscription
            $modulesNiveau = ModuleNiveau::where('niveau_id', $inscription->niveau_id)->get();

            if ($modulesNiveau->isEmpty()) {
                $this->command->warn(" - No modules found for this level! Skipping notes.");
                continue;
            }

            $gradesCount = 0;
            $sumGrades = 0;
            $countGrades = 0;

            foreach ($modulesNiveau as $moduleNiveau) {
                // Check if note exists
                $existingNote = Note::where('inscription_id', $inscription->id)
                    ->where('module_niveau_id', $moduleNiveau->id)
                    ->first();

                if (!$existingNote) {
                    $noteVal = rand(8, 18) + (rand(0, 99) / 100); // Random grade 8.00 - 18.99
                    $estValide = $noteVal >= 12; // Simple validation rule

                    Note::create([
                        'inscription_id' => $inscription->id,
                        'module_niveau_id' => $moduleNiveau->id,
                        'note' => $noteVal,
                        'est_valide' => $estValide,
                        'type_session' => 'Normale',
                        'date_saisie' => now()->subDays(rand(1, 30)),
                    ]);
                    $gradesCount++;
                    $sumGrades += $noteVal;
                    $countGrades++;
                }
            }

            if ($gradesCount > 0) {
                $this->command->info(" - Generated {$gradesCount} new notes.");
            } else {
                $this->command->info(" - Notes already exist.");
            }

            // 3. Generate Decision (if notes exist)
            // Recalculate average if we added notes or if decision missing
            $decision = DecisionAnnee::where('inscription_id', $inscription->id)->first();
            
            if (!$decision && $countGrades > 0) {
                 // Calculate simplified average (assuming coef 1 for now for speed, or fetch proper coef)
                 $avg = $countGrades > 0 ? $sumGrades / $countGrades : 10;
                 
                 DecisionAnnee::create([
                     'inscription_id' => $inscription->id,
                     'moyenne_annuelle' => $avg,
                     'decision' => $avg >= 12 ? 'Admis' : 'Ajourné',
                     'mention' => $this->getMention($avg),
                     'rang' => rand(1, 30),
                     'type_session' => 'Normale'
                 ]);
                 $this->command->info(" - Generated Decision: " . ($avg >= 12 ? 'Admis' : 'Ajourné'));
            }
        }
    }

    private function getMention($avg) {
        if ($avg >= 16) return 'Très Bien';
        if ($avg >= 14) return 'Bien';
        if ($avg >= 12) return 'Assez Bien';
        return 'Passable';
    }
}
