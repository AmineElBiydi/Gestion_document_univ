<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Demande;
use App\Models\Etudiant;
use App\Models\Inscription;
use App\Models\DecisionAnnee;
use App\Models\Professeur;
use Carbon\Carbon;

class DemandeSeeder extends Seeder
{
    public function run(): void
    {
        $etudiants = Etudiant::all();
        if ($etudiants->isEmpty()) {
            return;
        }

        $types = ['attestation_scolaire', 'attestation_reussite', 'releve_notes', 'convention_stage'];
        $statuses = ['en_attente', 'validee', 'rejetee'];
        $professeurs = Professeur::all();
        
        // Create 50 demandes with varied dates and statuses
        for ($i = 0; $i < 50; $i++) {
            $etudiant = $etudiants->random();
            $type = $types[array_rand($types)];
            $status = $statuses[array_rand($statuses)];
            
            // Create demandes over the past 3 months
            $createdAt = Carbon::now()->subDays(rand(1, 90));
            $updatedAt = $status === 'en_attente' ? $createdAt : $createdAt->copy()->addDays(rand(1, 10));
            
            // Get inscription if available (optional)
            $inscription = Inscription::where('etudiant_id', $etudiant->id)->inRandomOrder()->first();
            
            $demande = Demande::create([
                'etudiant_id' => $etudiant->id,
                'inscription_id' => $inscription?->id,
                'type_document' => $type,
                'status' => $status,
                'date_demande' => $createdAt->format('Y-m-d'),
                'num_demande' => 'DMD-' . $createdAt->format('Y') . '-' . str_pad(($i + 1), 4, '0', STR_PAD_LEFT),
                'date_traitement' => $status !== 'en_attente' ? $updatedAt : null,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
            
            // Create related document details based on type
            switch ($type) {
                case 'attestation_scolaire':
                    // Attestation scolaire now only needs demande_id
                    $demande->attestationScolaire()->create([
                        'demande_id' => $demande->id,
                    ]);
                    break;
                    
                case 'attestation_reussite':
                    // Requires decision_annee_id - only create if we have a decision
                    if ($inscription) {
                        $decision = DecisionAnnee::where('inscription_id', $inscription->id)->inRandomOrder()->first();
                        if ($decision) {
                            $demande->attestationReussite()->create([
                                'demande_id' => $demande->id,
                                'decision_annee_id' => $decision->id,
                            ]);
                        }
                    }
                    break;
                    
                case 'releve_notes':
                    // Requires decision_annee_id - only create if we have a decision
                    if ($inscription) {
                        $decision = DecisionAnnee::where('inscription_id', $inscription->id)->inRandomOrder()->first();
                        if ($decision) {
                            $demande->releveNotes()->create([
                                'demande_id' => $demande->id,
                                'decision_annee_id' => $decision->id,
                            ]);
                        }
                    }
                    break;
                    
                case 'convention_stage':
                    $professeur = $professeurs->isNotEmpty() ? $professeurs->random() : null;
                    $demande->conventionStage()->create([
                        'demande_id' => $demande->id,
                        'entreprise' => 'Tech Solutions SARL',
                        'adresse_entreprise' => '123 Rue Mohammed V, Casablanca',
                        'email_encadrant' => 'encadrant@techsolutions.ma',
                        'telephone_encadrant' => '0522345678',
                        'encadrant_entreprise' => 'M. Alami',
                        'encadrant_pedagogique_id' => $professeur?->id,
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