<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Demande;
use App\Models\Etudiant;
use Carbon\Carbon;

class DemandeSeeder extends Seeder
{
    public function run(): void
    {
        $etudiants = Etudiant::all();
        $types = ['attestation_scolaire', 'attestation_reussite', 'releve_notes', 'convention_stage'];
        $statuses = ['en_attente', 'validee', 'rejetee'];
        
        // Create 50 demandes with varied dates and statuses
        for ($i = 0; $i < 50; $i++) {
            $etudiant = $etudiants->random();
            $type = $types[array_rand($types)];
            $status = $statuses[array_rand($statuses)];
            
            // Create demandes over the past 3 months
            $createdAt = Carbon::now()->subDays(rand(1, 90));
            $updatedAt = $status === 'en_attente' ? $createdAt : $createdAt->copy()->addDays(rand(1, 10));
            
            $demande = Demande::create([
                'etudiant_id' => $etudiant->id,
                'type_document' => $type,
                'status' => $status,
                'date_demande' => $createdAt->format('Y-m-d'),
                'num_demande' => 'DMD-' . $createdAt->format('Y') . '-' . str_pad(($i + 1), 4, '0', STR_PAD_LEFT),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
            
            // Create related document details based on type
            switch ($type) {
                case 'attestation_scolaire':
                    $demande->attestationScolaire()->create([
                        'niveau' => 'L' . rand(1, 3),
                        'filiere' => 'Informatique',
                        'annee_universitaire' => '2023-2024',
                    ]);
                    break;
                case 'attestation_reussite':
                    $demande->attestationReussite()->create([
                        'filiere' => 'Informatique',
                        'annee_universitaire' => '2022-2023',
                        'cycle' => 'Licence',
                        'session' => 'Normale',
                        'type_releve' => 'Définitif',
                    ]);
                    break;
                case 'releve_notes':
                    $demande->releveNotes()->create([
                        'semestre' => 'S' . rand(1, 6),
                        'annee_universitaire' => '2023-2024',
                    ]);
                    break;
                case 'convention_stage':
                    $demande->conventionStage()->create([
                        'entreprise' => 'Tech Solutions SARL',
                        'adresse_entreprise' => '123 Rue Mohammed V, Casablanca',
                        'email_encadrant' => 'encadrant@techsolutions.ma',
                        'telephone_encadrant' => '0522345678',
                        'encadrant_entreprise' => 'M. Alami',
                        'encadrant_pedagogique' => 'Pr. Idrissi',
                        'fonction_encadrant' => 'Responsable Technique',
                        'sujet' => 'Développement d\'une application web de gestion',
                        'date_debut' => $createdAt->copy()->addMonth()->format('Y-m-d'),
                        'date_fin' => $createdAt->copy()->addMonths(rand(2, 4))->format('Y-m-d'),
                    ]);
                    break;
            }
        }
    }
}
