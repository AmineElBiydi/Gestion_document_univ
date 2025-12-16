<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reclamation;
use App\Models\Demande;
use App\Models\Etudiant;
use Carbon\Carbon;

class ReclamationSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['retard', 'document_incorrect', 'refus_injustifie', 'probleme_technique'];
        $statuses = ['non_traitee', 'en_cours', 'traitee'];
        
        // Create 25 reclamations
        for ($i = 0; $i < 25; $i++) {
            $demande = Demande::with('etudiant')->inRandomOrder()->first();
            $type = $types[array_rand($types)];
            $status = $statuses[array_rand($statuses)];
            
            $createdAt = Carbon::now()->subDays(rand(1, 60));
            $updatedAt = $status === 'non_traitee' ? $createdAt : $createdAt->copy()->addDays(rand(1, 15));
            
            $description = $this->getRandomDescription($type, $demande);
            $reponse = $status === 'traitee' ? $this->getRandomResponse($type) : null;
            
            Reclamation::create([
                'demande_id' => $demande->id,
                'etudiant_id' => $demande->etudiant_id,
                'type' => $type,
                'description' => $description,
                'status' => $status,
                'reponse' => $reponse,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
        }
    }
    
    private function getRandomDescription($type, $demande)
    {
        $descriptions = [
            'retard' => [
                'Ma demande est en attente depuis plus de 10 jours sans aucune mise à jour.',
                'Je n\'ai toujours pas reçu mon document après 2 semaines d\'attente.',
                'Le délai de traitement est beaucoup plus long que prévu.',
                'Pourriez-vous me donner une date approximative pour recevoir mon document ?',
            ],
            'document_incorrect' => [
                'Le relevé de notes reçu contient des erreurs dans les notes du module de mathématiques.',
                'Mon nom est mal orthographié sur l\'attestation scolaire.',
                'Les informations sur la convention de stage ne sont pas correctes.',
                'La date sur l\'attestation de réussite est erronée.',
            ],
            'refus_injustifie' => [
                'Ma demande a été refusée mais je ne comprends pas pourquoi. Tous mes documents sont en règle.',
                'Le refus semble injustifié car je suis bien inscrit cette année.',
                'Pourriez-vous m\'expliquer les raisons du refus de ma demande ?',
                'Je pense que ma demande a été refusée par erreur.',
            ],
            'probleme_technique' => [
                'Je souhaiterais modifier les informations sur ma demande si possible.',
                'J\'ai besoin du document de manière urgente pour une procédure administrative.',
                'Pourriez-vous m\'aider à suivre l\'état de ma demande ?',
                'J\'ai des questions concernant les documents requis.',
            ],
        ];
        
        return $descriptions[$type][array_rand($descriptions[$type])];
    }
    
    private function getRandomResponse($type)
    {
        $responses = [
            'retard' => [
                'Nous sommes désolés pour le retard. Votre demande est maintenant prioritaire et sera traitée dans les 48h.',
                'Suite à votre réclamation, nous avons accéléré le traitement de votre dossier.',
                'Le retard était dû à un afflux de demandes. Votre document est prêt et vous sera envoyé rapidement.',
            ],
            'document_incorrect' => [
                'Après vérification, nous avons corrigé les erreurs. Un nouveau document vous sera envoyé.',
                'Les informations ont été mises à jour selon vos indications. Veuillez vérifier le nouveau document.',
                'Nous avons pris en compte vos remarques et le document a été réédité.',
            ],
            'refus_injustifie' => [
                'Après vérification, votre demande a été réactivée. Nous nous excusons pour ce désagrément.',
                'Le refus était dû à une erreur de traitement. Votre demande est maintenant acceptée.',
                'Nous avons réexaminé votre dossier et il est maintenant validé.',
            ],
            'probleme_technique' => [
                'Votre demande a été prise en compte et nos services vous contacteront prochainement.',
                'Nous avons bien reçu votre réclamation et y donnons suite dans les meilleurs délais.',
                'Merci pour votre patience, le problème a été résolu.',
            ],
        ];
        
        return $responses[$type][array_rand($responses[$type])];
    }
}
