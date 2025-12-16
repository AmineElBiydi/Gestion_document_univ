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
                'nom' => 'Alami',
                'prenom' => 'Mohammed',
                'apogee' => '12345678',
                'cin' => 'AB123456',
                'email' => 'student@universite.ma',
                'status' => 'actif',
            ],
            [
                'nom' => 'Bennani',
                'prenom' => 'Fatima',
                'apogee' => '87654321',
                'cin' => 'CD789012',
                'email' => 'etudiant@universite.ma',
                'status' => 'actif',
            ],
            [
                'nom' => 'Idrissi',
                'prenom' => 'Youssef',
                'apogee' => '11223344',
                'cin' => 'EF345678',
                'email' => 'youssef.idrissi@universite.ma',
                'status' => 'actif',
            ],
            [
                'nom' => 'Tazi',
                'prenom' => 'Amina',
                'apogee' => '55667788',
                'cin' => 'GH901234',
                'email' => 'amina.tazi@universite.ma',
                'status' => 'actif',
            ],
            [
                'nom' => 'Fassi',
                'prenom' => 'Karim',
                'apogee' => '99887766',
                'cin' => 'IJ567890',
                'email' => 'karim.fassi@universite.ma',
                'status' => 'diplome',
            ],
            [
                'nom' => 'El Amrani',
                'prenom' => 'Sara',
                'apogee' => '23456789',
                'cin' => 'KL234567',
                'email' => 'sara.elamrani@universite.ma',
                'status' => 'actif',
            ],
            [
                'nom' => 'Mansouri',
                'prenom' => 'Omar',
                'apogee' => '34567890',
                'cin' => 'MN345678',
                'email' => 'omar.mansouri@universite.ma',
                'status' => 'actif',
            ],
            [
                'nom' => 'Cherkaoui',
                'prenom' => 'Leila',
                'apogee' => '45678901',
                'cin' => 'OP456789',
                'email' => 'leila.cherkaoui@universite.ma',
                'status' => 'actif',
            ],
            [
                'nom' => 'Jabrane',
                'prenom' => 'Ahmed',
                'apogee' => '56789012',
                'cin' => 'QR567890',
                'email' => 'ahmed.jabrane@universite.ma',
                'status' => 'actif',
            ],
            [
                'nom' => 'Kabbouri',
                'prenom' => 'Nadia',
                'apogee' => '67890123',
                'cin' => 'ST678901',
                'email' => 'nadia.kabbouri@universite.ma',
                'status' => 'diplome',
            ],
            [
                'nom' => 'Bensalem',
                'prenom' => 'Mehdi',
                'apogee' => '78901234',
                'cin' => 'UV789012',
                'email' => 'mehdi.bensalem@universite.ma',
                'status' => 'actif',
            ],
            [
                'nom' => 'El Fihri',
                'prenom' => 'Khadija',
                'apogee' => '89012345',
                'cin' => 'WX890123',
                'email' => 'khadija.elfihri@universite.ma',
                'status' => 'actif',
            ],
            [
                'nom' => 'Rafik',
                'prenom' => 'Hassan',
                'apogee' => '90123456',
                'cin' => 'YZ901234',
                'email' => 'hassan.rafik@universite.ma',
                'status' => 'actif',
            ],
            [
                'nom' => 'Moussaoui',
                'prenom' => 'Imane',
                'apogee' => '01234567',
                'cin' => 'ZA012345',
                'email' => 'imane.moussaoui@universite.ma',
                'status' => 'actif',
            ],
            [
                'nom' => 'Boudhrioua',
                'prenom' => 'Younes',
                'apogee' => '13579246',
                'cin' => 'BC135792',
                'email' => 'younes.boudhrioua@universite.ma',
                'status' => 'diplome',
            ],
        ];

        foreach ($etudiants as $etudiant) {
            Etudiant::create($etudiant);
        }
    }
}
