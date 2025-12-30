<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfesseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $professeurs = [
            // Professeurs d'Informatique
            [
                'matricule' => 'PROF003',
                'nom' => 'CHAKIR',
                'prenom' => 'Mohamed',
                'email' => 'm.chakir@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 03',
                'specialite' => 'Génie Logiciel',
                'grade' => 'Professeur Habilité',
                'est_actif' => true,
            ],
            [
                'matricule' => 'PROF004',
                'nom' => 'DRISSI',
                'prenom' => 'Amina',
                'email' => 'a.drissi@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 04',
                'specialite' => 'Intelligence Artificielle',
                'grade' => 'Professeur',
                'est_actif' => true,
            ],
            [
                'matricule' => 'PROF005',
                'nom' => 'EL AMRANI',
                'prenom' => 'Youssef',
                'email' => 'y.elamrani@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 05',
                'specialite' => 'Réseaux et Sécurité',
                'grade' => 'Professeur Assistant',
                'est_actif' => true,
            ],
            [
                'matricule' => 'PROF006',
                'nom' => 'FASSI',
                'prenom' => 'Karim',
                'email' => 'k.fassi@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 06',
                'specialite' => 'Bases de Données',
                'grade' => 'Professeur Assistant',
                'est_actif' => true,
            ],
            [
                'matricule' => 'PROF007',
                'nom' => 'GHARBI',
                'prenom' => 'Salma',
                'email' => 's.gharbi@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 07',
                'specialite' => 'Développement Web',
                'grade' => 'Professeur Associé',
                'est_actif' => true,
            ],

        ];

        foreach ($professeurs as $professeur) {
            DB::table('professeurs')->insert(array_merge($professeur, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
