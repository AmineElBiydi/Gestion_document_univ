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

        // Current year (2024-2025) inscriptions
        $currentYear = $annees['2024-2025']->id;

        // CP1 Students
        $cp1Students = ['12345678', '87654321', '11223344', '55667788'];
        foreach ($cp1Students as $apogee) {
            $inscriptions[] = [
                'etudiant_id' => $etudiants[$apogee]->id,
                'annee_id' => $currentYear,
                'filiere_id' => $filieres['CP']->id,
                'niveau_id' => $niveaux['CP1']->id,
                'date_inscription' => '2024-09-15',
                'statut' => 'inscrit',
            ];
        }

        // CP2 Students
        $cp2Students = ['23456789', '34567890', '45678901'];
        foreach ($cp2Students as $apogee) {
            $inscriptions[] = [
                'etudiant_id' => $etudiants[$apogee]->id,
                'annee_id' => $currentYear,
                'filiere_id' => $filieres['CP']->id,
                'niveau_id' => $niveaux['CP2']->id,
                'date_inscription' => '2024-09-15',
                'statut' => 'inscrit',
            ];
        }

        // GI - CI1 Students
        $giCI1Students = ['56789012', '78901234', '24681357'];
        foreach ($giCI1Students as $apogee) {
            $inscriptions[] = [
                'etudiant_id' => $etudiants[$apogee]->id,
                'annee_id' => $currentYear,
                'filiere_id' => $filieres['GI']->id,
                'niveau_id' => $niveaux['CI1']->id,
                'date_inscription' => '2024-09-15',
                'statut' => 'inscrit',
            ];
        }

        // GI - CI2 Students
        $giCI2Students = ['89012345', '90123456', '36925814'];
        foreach ($giCI2Students as $apogee) {
            $inscriptions[] = [
                'etudiant_id' => $etudiants[$apogee]->id,
                'annee_id' => $currentYear,
                'filiere_id' => $filieres['GI']->id,
                'niveau_id' => $niveaux['CI2']->id,
                'date_inscription' => '2024-09-15',
                'statut' => 'inscrit',
            ];
        }

        // GI - CI3 Students
        $giCI3Students = ['01234567', '14725836'];
        foreach ($giCI3Students as $apogee) {
            $inscriptions[] = [
                'etudiant_id' => $etudiants[$apogee]->id,
                'annee_id' => $currentYear,
                'filiere_id' => $filieres['GI']->id,
                'niveau_id' => $niveaux['CI3']->id,
                'date_inscription' => '2024-09-15',
                'statut' => 'inscrit',
            ];
        }

        // GSECS - CI1 Students
        $gsecsCI1Students = ['25836914', '36914725'];
        foreach ($gsecsCI1Students as $apogee) {
            $inscriptions[] = [
                'etudiant_id' => $etudiants[$apogee]->id,
                'annee_id' => $currentYear,
                'filiere_id' => $filieres['GSECS']->id,
                'niveau_id' => $niveaux['CI1']->id,
                'date_inscription' => '2024-09-15',
                'statut' => 'inscrit',
            ];
        }

        // Previous year (2023-2024) inscriptions for graduated students
        $previousYear = $annees['2023-2024']->id;

        // Graduated students
        $graduatedStudents = [
            ['99887766', 'GI', 'CI3'],
            ['67890123', 'GI', 'CI3'],
            ['13579246', 'GSECS', 'CI3'],
        ];

        foreach ($graduatedStudents as [$apogee, $filiereCode, $niveauCode]) {
            $inscriptions[] = [
                'etudiant_id' => $etudiants[$apogee]->id,
                'annee_id' => $previousYear,
                'filiere_id' => $filieres[$filiereCode]->id,
                'niveau_id' => $niveaux[$niveauCode]->id,
                'date_inscription' => '2023-09-15',
                'statut' => 'diplome',
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
