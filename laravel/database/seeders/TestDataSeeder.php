<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Etudiant;
use App\Models\AnneeUniversitaire;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Module;
use App\Models\ModuleNiveau;
use App\Models\Inscription;
use App\Models\Note;
use App\Models\DecisionAnnee;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Créer une année universitaire
        $annee = AnneeUniversitaire::firstOrCreate(
            ['libelle' => '2024-2025'],
            [
                'date_debut' => '2024-09-01',
                'date_fin' => '2025-06-30',
                'est_active' => true
            ]
        );

        // 2. Créer une filière
        $filiere = Filiere::firstOrCreate(
            ['code_filiere' => 'INFO'],
            ['nom_filiere' => 'Informatique']
        );

        // 3. Créer un niveau
        $niveau = Niveau::firstOrCreate(
            ['code_niveau' => 'S1'],
            ['libelle' => 'Semestre 1', 'ordre' => 1]
        );

        // 4. Créer des modules
        $modules = [
            ['code' => 'M1', 'nom' => 'Analyse 1', 'credits' => 4],
            ['code' => 'M2', 'nom' => 'Algèbre 1', 'credits' => 4],
            ['code' => 'M3', 'nom' => 'Algorithmique', 'credits' => 4],
            ['code' => 'M4', 'nom' => 'Physique 1', 'credits' => 4],
            ['code' => 'M5', 'nom' => 'Informatique 1', 'credits' => 4],
        ];

        $moduleNiveaux = [];
        foreach ($modules as $modData) {
            $module = Module::firstOrCreate(
                ['code_module' => $modData['code']],
                ['nom_module' => $modData['nom'], 'credits' => $modData['credits']]
            );

            $moduleNiveau = ModuleNiveau::firstOrCreate(
                [
                    'module_id' => $module->id,
                    'niveau_id' => $niveau->id,
                    'filiere_id' => $filiere->id
                ],
                ['coefficient' => 1]
            );

            $moduleNiveaux[] = $moduleNiveau;
        }

        // 5. Créer un étudiant
        $etudiant = Etudiant::firstOrCreate(
            ['apogee' => '20240001'],
            [
                'nom' => 'ALAMI',
                'prenom' => 'Ahmed',
                'cin' => 'AB123456',
                'email' => 'ahmed.alami@universite.ma',
                'date_naissance' => '2004-05-15',
                'lieu_naissance' => 'Casablanca',
                'telephone' => '0612345678',
                'adresse' => '123 Bd Mohamed V, Casablanca',
                'pays' => 'Maroc',
                'status' => 'actif'
            ]
        );

        // 6. Créer une inscription
        $inscription = Inscription::firstOrCreate(
            [
                'etudiant_id' => $etudiant->id,
                'annee_id' => $annee->id
            ],
            [
                'filiere_id' => $filiere->id,
                'niveau_id' => $niveau->id,
                'date_inscription' => '2024-09-01',
                'statut' => 'inscrit'
            ]
        );

        // 7. Créer des notes
        $notes = [15, 14, 16, 13, 17];
        $totalNotes = 0;
        $totalCredits = 0;

        foreach ($moduleNiveaux as $index => $moduleNiveau) {
            $noteValue = $notes[$index];
            $totalNotes += $noteValue * $moduleNiveau->module->credits;
            $totalCredits += $moduleNiveau->module->credits;

            Note::firstOrCreate(
                [
                    'inscription_id' => $inscription->id,
                    'module_niveau_id' => $moduleNiveau->id
                ],
                [
                    'type_session' => 'normale',
                    'note' => $noteValue,
                    'est_valide' => $noteValue >= 10
                ]
            );
        }

        // 8. Créer une décision d'année
        $moyenne = $totalNotes / $totalCredits;
        $mention = $moyenne >= 16 ? 'Très Bien' : 
                   ($moyenne >= 14 ? 'Bien' : 
                   ($moyenne >= 12 ? 'Assez Bien' : 'Passable'));

        DecisionAnnee::updateOrCreate(
            ['inscription_id' => $inscription->id],
            [
                'type_session' => 'normale',
                'moyenne_annuelle' => $moyenne,
                'credits_valides' => $totalCredits,
                'credits_totaux' => $totalCredits,
                'mention' => $mention,
                'decision' => 'admis',
                'date_decision' => '2025-06-30'
            ]
        );

        $this->command->info('✅ Données de test créées avec succès !');
        $this->command->info("📧 Email: {$etudiant->email}");
        $this->command->info("🎓 Apogée: {$etudiant->apogee}");
        $this->command->info("🆔 CIN: {$etudiant->cin}");
        $this->command->info("📊 Moyenne: " . number_format($moyenne, 2));
    }
}
