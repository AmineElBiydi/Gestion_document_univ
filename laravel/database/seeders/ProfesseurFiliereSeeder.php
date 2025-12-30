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
