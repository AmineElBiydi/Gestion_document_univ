<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inscription;
use App\Models\Module;
use App\Models\ModuleNiveau;
use App\Models\Note;
use App\Models\DecisionAnnee;
use App\Models\Filiere;
use App\Models\Niveau;

class CompleteStudentDataSeeder extends Seeder
{
    /**
     * Compléter les données des étudiants existants avec notes et décisions
     */
    public function run(): void
    {
        $this->command->info('🔄 Création des modules de base...');
        
        // Récupérer ou créer les modules
        $modulesData = [
            ['code' => 'M1', 'nom' => 'Analyse Mathématique', 'credits' => 4],
            ['code' => 'M2', 'nom' => 'Algèbre', 'credits' => 4],
            ['code' => 'M3', 'nom' => 'Algorithmique et Programmation', 'credits' => 4],
            ['code' => 'M4', 'nom' => 'Architecture des Ordinateurs', 'credits' => 4],
            ['code' => 'M5', 'nom' => 'Systèmes d\'Exploitation', 'credits' => 4],
            ['code' => 'M6', 'nom' => 'Bases de Données', 'credits' => 4],
        ];

        $modules = [];
        foreach ($modulesData as $modData) {
            $modules[] = Module::firstOrCreate(
                ['code_module' => $modData['code']],
                [
                    'nom_module' => $modData['nom'],
                    'credits' => $modData['credits']
                ]
            );
        }

        $this->command->info('✅ ' . count($modules) . ' modules créés/récupérés');

        // Récupérer toutes les inscriptions
        $inscriptions = Inscription::with(['filiere', 'niveau'])->get();
        
        if ($inscriptions->isEmpty()) {
            $this->command->error('❌ Aucune inscription trouvée !');
            return;
        }

        $this->command->info("📚 {$inscriptions->count()} inscriptions trouvées");

        $decisionsCreated = 0;
        $notesCreated = 0;

        foreach ($inscriptions as $inscription) {
            // Vérifier si une décision existe déjà
            if ($inscription->decisionsAnnee()->exists()) {
                continue;
            }

            $filiere = $inscription->filiere;
            $niveau = $inscription->niveau;

            if (!$filiere || !$niveau) {
                $this->command->warn("⚠️ Inscription {$inscription->id} sans filière ou niveau");
                continue;
            }

            // Créer les liens Module-Niveau si nécessaire
            $moduleNiveaux = [];
            foreach ($modules as $module) {
                $mn = ModuleNiveau::firstOrCreate(
                    [
                        'module_id' => $module->id,
                        'niveau_id' => $niveau->id,
                        'filiere_id' => $filiere->id
                    ],
                    [
                        'coefficient' => 1,
                        'est_obligatoire' => true
                    ]
                );
                $moduleNiveaux[] = $mn;
            }

            // Créer des notes aléatoires mais réalistes
            $totalNotes = 0;
            $totalCredits = 0;

            foreach ($moduleNiveaux as $moduleNiveau) {
                // Notes entre 10 et 18
                $noteValue = rand(100, 180) / 10;
                
                Note::create([
                    'inscription_id' => $inscription->id,
                    'module_niveau_id' => $moduleNiveau->id,
                    'type_session' => 'normale',
                    'note' => $noteValue,
                    'est_valide' => $noteValue >= 10
                ]);

                $totalNotes += $noteValue * $moduleNiveau->module->credits;
                $totalCredits += $moduleNiveau->module->credits;
                $notesCreated++;
            }

            // Créer la décision d'année
            $moyenne = $totalNotes / $totalCredits;
            
            // Déterminer la mention
            if ($moyenne >= 16) {
                $mention = 'Très Bien';
            } elseif ($moyenne >= 14) {
                $mention = 'Bien';
            } elseif ($moyenne >= 12) {
                $mention = 'Assez Bien';
            } else {
                $mention = 'Passable';
            }

            DecisionAnnee::create([
                'inscription_id' => $inscription->id,
                'type_session' => 'normale',
                'moyenne_annuelle' => round($moyenne, 2),
                'credits_valides' => $totalCredits,
                'credits_totaux' => $totalCredits,
                'mention' => $mention,
                'decision' => $moyenne >= 10 ? 'admis' : 'ajourné',
                'date_decision' => now()->subDays(rand(1, 30))
            ]);

            $decisionsCreated++;
        }

        $this->command->info('');
        $this->command->info('✅ RÉSULTATS :');
        $this->command->info("   📝 {$notesCreated} notes créées");
        $this->command->info("   🎓 {$decisionsCreated} décisions créées");
        $this->command->info('');
        $this->command->info('🎉 Les données sont maintenant complètes ! Vous pouvez tester l\'envoi de PDF.');
    }
}
