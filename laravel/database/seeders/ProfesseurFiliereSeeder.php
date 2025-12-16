<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfesseurFiliereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get IDs for reference
        $professeurs = DB::table('professeurs')->get()->keyBy('matricule');
        $filieres = DB::table('filieres')->get()->keyBy('code_filiere');

        $professeursFilieres = [
            // Génie Informatique
            ['PROF003', 'GI', 'responsable'],  // Chakir - Responsable GI
            ['PROF004', 'GI', 'coordinateur'], // Drissi
            ['PROF005', 'GI', 'enseignant'],   // El Amrani
            ['PROF006', 'GI', 'enseignant'],   // Fassi
            ['PROF007', 'GI', 'enseignant'],   // Gharbi

            // Génie Système Embarqué et Cyber Security
            ['PROF009', 'GSECS', 'responsable'],  // Idrissi - Responsable GSECS
            ['PROF010', 'GSECS', 'coordinateur'], // Jaber
            ['PROF011', 'GSECS', 'enseignant'],   // Kabbaj
            ['PROF019', 'GSECS', 'enseignant'],   // Tazi

            // Génie Mécatronique
            ['PROF012', 'GM', 'responsable'],  // Lahlou - Responsable GM
            ['PROF013', 'GM', 'coordinateur'], // Mansouri
            ['PROF014', 'GM', 'enseignant'],   // Naciri

            // Génie Civil
            ['PROF015', 'GC', 'responsable'],  // Ouazzani - Responsable GC
            ['PROF016', 'GC', 'coordinateur'], // Qadiri

            // Big Data et IA
            ['PROF004', 'BDIA', 'responsable'],  // Drissi - Responsable BDIA
            ['PROF003', 'BDIA', 'coordinateur'], // Chakir
            ['PROF020', 'BDIA', 'enseignant'],   // Wahbi

            // Supply Chain Management
            ['PROF012', 'SCM', 'coordinateur'], // Lahlou (also in GM)
            ['PROF013', 'SCM', 'enseignant'],   // Mansouri (also in GM)

            // Cycle Préparatoire
            ['PROF001', 'CP', 'coordinateur'], // Alami - Math
            ['PROF002', 'CP', 'enseignant'],   // Benali - Math
            ['PROF008', 'CP', 'enseignant'],   // Hamdi - Physique
            ['PROF017', 'CP', 'enseignant'],   // Rami - Langues
            ['PROF018', 'CP', 'enseignant'],   // Slimani - Anglais

            // Masters
            ['PROF004', 'MIAGE', 'responsable'], // Drissi - Responsable MIAGE
            ['PROF003', 'MSI', 'responsable'],   // Chakir - Responsable MSI
        ];

        foreach ($professeursFilieres as [$matricule, $codeFiliere, $role]) {
            DB::table('professeurs_filieres')->insert([
                'professeur_id' => $professeurs[$matricule]->id,
                'filiere_id' => $filieres[$codeFiliere]->id,
                'role' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
