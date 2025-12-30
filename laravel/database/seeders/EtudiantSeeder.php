<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Etudiant;

class EtudiantSeeder extends Seeder
{
    public function run(): void
    {
        $etudiants = [
            [
                'nom' => 'Lyamani',
                'prenom' => 'Ismail',
                'apogee' => '22050001',
                'cin' => 'AB123456',
                'email' => 'ismail.lyamani@etu.uae.ac.ma',
                'date_naissance' => '2003-05-15',
                'lieu_naissance' => 'Casablanca',
                'telephone' => '+212 6 11 22 33 01',
                'adresse' => '25 Rue des Écoles, Casablanca',
                'status' => 'actif',
            ],
            [
                'nom' => 'Oumhella',
                'prenom' => 'Abdellatif',
                'apogee' => '22050002',
                'cin' => 'JA196353',
                'email' => 'abdellatif.oumhella@etu.uae.ac.ma',
                'date_naissance' => '2004-03-22',
                'lieu_naissance' => 'Rabat',
                'telephone' => '+212 6 11 22 33 02',
                'adresse' => '12 Avenue Mohammed V, Rabat',
                'status' => 'actif',
            ],
            [
                'nom' => 'Saber',
                'prenom' => 'Mohammed Aymane',
                'apogee' => '11223344',
                'cin' => 'EF345678',
                'email' => 'youssef.idrissi@etu.uae.ac.ma',
                'date_naissance' => '2003-11-08',
                'lieu_naissance' => 'Fès',
                'telephone' => '+212 6 11 22 33 03',
                'adresse' => '8 Quartier Zouagha, Fès',
                'status' => 'actif',
            ],
            [
                'nom' => 'El Allouche',
                'prenom' => 'Zakariyae',
                'apogee' => '55667788',
                'cin' => 'GH901234',
                'email' => 'elallouche.zakariyae@etu.uae.ac.ma',
                'date_naissance' => '2004-07-19',
                'lieu_naissance' => 'Marrakech',
                'telephone' => '+212 6 11 22 33 04',
                'adresse' => '34 Rue de la Liberté, Marrakech',
                'status' => 'actif',
            ],
            [
                'nom' => 'El Biyadi',
                'prenom' => 'Amine',
                'apogee' => '22050003',
                'cin' => 'IJ567890',
                'email' => 'amine.elbiyadi@etu.uae.ac.ma',
                'date_naissance' => '2003-02-14',
                'lieu_naissance' => 'Fès',
                'telephone' => '+212 6 11 22 33 05',
                'adresse' => '56 Boulevard Hassan II, Fès',
                'status' => 'actif',
            ],
            [
                'nom' => 'Khafou',
                'prenom' => 'Omar',
                'apogee' => '23456789',
                'cin' => 'KL234567',
                'email' => 'khafou.omar@etu.uae.ac.ma',
                'date_naissance' => '2003-09-30',
                'lieu_naissance' => 'Tanger',
                'telephone' => '+212 6 11 22 33 06',
                'adresse' => '17 Avenue Ibn Batouta, Tanger',
                'status' => 'actif',
            ],
            [
                'nom' => 'Tayeb',
                'prenom' => 'Massoumi',
                'apogee' => '34567890',
                'cin' => 'MN345678',
                'email' => 'tayeb.massoumi@etu.uae.ac.ma',
                'date_naissance' => '2004-01-25',
                'lieu_naissance' => 'Meknès',
                'telephone' => '+212 6 11 22 33 07',
                'adresse' => '42 Rue Rouamzine, Meknès',
                'status' => 'actif',
            ],
        ];

        foreach ($etudiants as $etudiant) {
            Etudiant::create($etudiant);
        }
    }
}
