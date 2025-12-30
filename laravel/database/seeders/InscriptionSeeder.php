<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get IDs for reference
        $etudiants = DB::table('etudiants')->get()->keyBy('apogee');
        $filieres = DB::table('filieres')->get()->keyBy('code_filiere');
        $niveaux = DB::table('niveaux')->get()->keyBy('code_niveau');
        $annees = DB::table('annees_universitaires')->get()->keyBy('libelle');

        $inscriptions = [];

        // Historical inscriptions for Ismail, Abdellatif, and Amine (started in 2022)
        $historicalStudents = ['22050001', '22050002', '22050003']; // Ismail, Abdellatif, Amine

        // 2022-2023: CP1 (2AP1)
        $year2022 = $annees['2022-2023']->id;
        foreach ($historicalStudents as $apogee) {
            $inscriptions[] = [
                'etudiant_id' => $etudiants[$apogee]->id,
                'annee_id' => $year2022,
                'filiere_id' => $filieres['2AP']->id,
                'niveau_id' => $niveaux['CP1']->id,
                'date_inscription' => '2022-09-15',
                'statut' => 'inscrit',
            ];
        }

        // 2023-2024: CP2 (2AP2)
        $year2023 = $annees['2023-2024']->id;
        foreach ($historicalStudents as $apogee) {
            $inscriptions[] = [
                'etudiant_id' => $etudiants[$apogee]->id,
                'annee_id' => $year2023,
                'filiere_id' => $filieres['2AP']->id,
                'niveau_id' => $niveaux['CP2']->id,
                'date_inscription' => '2023-09-15',
                'statut' => 'inscrit',
            ];
        }

        // 2024-2025: CI1 (GI1)
        $year2024 = $annees['2024-2025']->id;
        foreach ($historicalStudents as $apogee) {
            $inscriptions[] = [
                'etudiant_id' => $etudiants[$apogee]->id,
                'annee_id' => $year2024,
                'filiere_id' => $filieres['GI']->id,
                'niveau_id' => $niveaux['CI1']->id,
                'date_inscription' => '2024-09-15',
                'statut' => 'inscrit',
            ];
        }

        // 2025-2026: CI2 (GI2) - Current year
        $currentYear = $annees['2025-2026']->id;
        foreach ($historicalStudents as $apogee) {
            $inscriptions[] = [
                'etudiant_id' => $etudiants[$apogee]->id,
                'annee_id' => $currentYear,
                'filiere_id' => $filieres['GI']->id,
                'niveau_id' => $niveaux['CI2']->id,
                'date_inscription' => '2025-09-15',
                'statut' => 'inscrit',
            ];
        }

        // Insert all inscriptions
        foreach ($inscriptions as $inscription) {
            DB::table('inscriptions')->insert(array_merge($inscription, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
