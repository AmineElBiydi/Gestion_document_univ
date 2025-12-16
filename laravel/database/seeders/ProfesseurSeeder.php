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
            // Professeurs de Mathématiques
            [
                'matricule' => 'PROF001',
                'nom' => 'ALAMI',
                'prenom' => 'Hassan',
                'email' => 'h.alami@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 01',
                'specialite' => 'Mathématiques Appliquées',
                'grade' => 'Professeur Habilité',
                'est_actif' => true,
            ],
            [
                'matricule' => 'PROF002',
                'nom' => 'BENALI',
                'prenom' => 'Fatima',
                'email' => 'f.benali@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 02',
                'specialite' => 'Algèbre et Analyse',
                'grade' => 'Professeur',
                'est_actif' => true,
            ],

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

            // Professeurs de Physique
            [
                'matricule' => 'PROF008',
                'nom' => 'HAMDI',
                'prenom' => 'Ahmed',
                'email' => 'a.hamdi@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 08',
                'specialite' => 'Physique Fondamentale',
                'grade' => 'Professeur',
                'est_actif' => true,
            ],

            // Professeurs de Génie Électrique
            [
                'matricule' => 'PROF009',
                'nom' => 'IDRISSI',
                'prenom' => 'Rachid',
                'email' => 'r.idrissi@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 09',
                'specialite' => 'Électronique et Automatique',
                'grade' => 'Professeur Habilité',
                'est_actif' => true,
            ],
            [
                'matricule' => 'PROF010',
                'nom' => 'JABER',
                'prenom' => 'Nadia',
                'email' => 'n.jaber@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 10',
                'specialite' => 'Traitement du Signal',
                'grade' => 'Professeur',
                'est_actif' => true,
            ],
            [
                'matricule' => 'PROF011',
                'nom' => 'KABBAJ',
                'prenom' => 'Omar',
                'email' => 'o.kabbaj@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 11',
                'specialite' => 'Machines Électriques',
                'grade' => 'Professeur Assistant',
                'est_actif' => true,
            ],

            // Professeurs de Génie Mécanique
            [
                'matricule' => 'PROF012',
                'nom' => 'LAHLOU',
                'prenom' => 'Samir',
                'email' => 's.lahlou@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 12',
                'specialite' => 'Mécanique des Structures',
                'grade' => 'Professeur Habilité',
                'est_actif' => true,
            ],
            [
                'matricule' => 'PROF013',
                'nom' => 'MANSOURI',
                'prenom' => 'Leila',
                'email' => 'l.mansouri@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 13',
                'specialite' => 'Thermodynamique',
                'grade' => 'Professeur',
                'est_actif' => true,
            ],
            [
                'matricule' => 'PROF014',
                'nom' => 'NACIRI',
                'prenom' => 'Hicham',
                'email' => 'h.naciri@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 14',
                'specialite' => 'Fabrication Mécanique',
                'grade' => 'Professeur Assistant',
                'est_actif' => true,
            ],

            // Professeurs de Génie Civil
            [
                'matricule' => 'PROF015',
                'nom' => 'OUAZZANI',
                'prenom' => 'Khalid',
                'email' => 'k.ouazzani@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 15',
                'specialite' => 'Génie Civil et Construction',
                'grade' => 'Professeur Habilité',
                'est_actif' => true,
            ],
            [
                'matricule' => 'PROF016',
                'nom' => 'QADIRI',
                'prenom' => 'Samira',
                'email' => 's.qadiri@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 16',
                'specialite' => 'Géotechnique',
                'grade' => 'Professeur',
                'est_actif' => true,
            ],

            // Professeurs de Langues et Communication
            [
                'matricule' => 'PROF017',
                'nom' => 'RAMI',
                'prenom' => 'Sarah',
                'email' => 's.rami@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 17',
                'specialite' => 'Langues et Communication',
                'grade' => 'Professeur Associé',
                'est_actif' => true,
            ],
            [
                'matricule' => 'PROF018',
                'nom' => 'SLIMANI',
                'prenom' => 'Mehdi',
                'email' => 'm.slimani@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 18',
                'specialite' => 'Anglais Technique',
                'grade' => 'Professeur Associé',
                'est_actif' => true,
            ],

            // Professeurs supplémentaires
            [
                'matricule' => 'PROF019',
                'nom' => 'TAZI',
                'prenom' => 'Imane',
                'email' => 'i.tazi@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 19',
                'specialite' => 'Systèmes Embarqués',
                'grade' => 'Professeur Assistant',
                'est_actif' => true,
            ],
            [
                'matricule' => 'PROF020',
                'nom' => 'WAHBI',
                'prenom' => 'Tarik',
                'email' => 't.wahbi@ensa.ac.ma',
                'telephone' => '+212 6 12 34 56 20',
                'specialite' => 'Cloud Computing',
                'grade' => 'Professeur Assistant',
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
