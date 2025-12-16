<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FiliereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filieres = [
            // Cycle Préparatoire
            [
                'code_filiere' => 'CP',
                'nom_filiere' => 'Cycle Préparatoire',
                'cycle' => 'CP',
                'description' => 'Cycle préparatoire intégré - Formation de base en sciences et techniques',
                'est_active' => true,
            ],

            // Cycle Ingénieur
            [
                'code_filiere' => 'GI',
                'nom_filiere' => 'Génie Informatique',
                'cycle' => 'CI',
                'description' => 'Formation d\'ingénieurs en informatique et systèmes d\'information',
                'est_active' => true,
            ],
            [
                'code_filiere' => 'GSECS',
                'nom_filiere' => 'Génie Système Embarqué et Cyber Security',
                'cycle' => 'CI',
                'description' => 'Formation d\'ingénieurs en systèmes embarqués et cybersécurité',
                'est_active' => true,
            ],
            [
                'code_filiere' => 'GM',
                'nom_filiere' => 'Génie Mécatronique',
                'cycle' => 'CI',
                'description' => 'Formation d\'ingénieurs en mécatronique et automatisation',
                'est_active' => true,
            ],
            [
                'code_filiere' => 'GC',
                'nom_filiere' => 'Génie Civil',
                'cycle' => 'CI',
                'description' => 'Formation d\'ingénieurs en construction et travaux publics',
                'est_active' => true,
            ],
            [
                'code_filiere' => 'SCM',
                'nom_filiere' => 'Supply Chain Management',
                'cycle' => 'CI',
                'description' => 'Formation d\'ingénieurs en logistique et gestion de la chaîne d\'approvisionnement',
                'est_active' => true,
            ],
            [
                'code_filiere' => 'BDIA',
                'nom_filiere' => 'Big Data et Intelligence Artificielle',
                'cycle' => 'CI',
                'description' => 'Formation d\'ingénieurs en Big Data, IA et science des données',
                'est_active' => true,
            ],

            // Masters
            [
                'code_filiere' => 'MIAGE',
                'nom_filiere' => 'Master MIAGE',
                'cycle' => 'Master',
                'description' => 'Master en Informatique Appliquée à la Gestion des Entreprises',
                'est_active' => true,
            ],
            [
                'code_filiere' => 'MSI',
                'nom_filiere' => 'Master Systèmes Intelligents',
                'cycle' => 'Master',
                'description' => 'Master en Intelligence Artificielle et Systèmes Intelligents',
                'est_active' => true,
            ],
        ];

        foreach ($filieres as $filiere) {
            DB::table('filieres')->insert(array_merge($filiere, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
