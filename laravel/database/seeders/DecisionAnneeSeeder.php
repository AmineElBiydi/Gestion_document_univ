<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DecisionAnneeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all inscriptions with their notes
        $inscriptions = DB::table('inscriptions')->get();

        $decisions = [];

        foreach ($inscriptions as $inscription) {
            // Get all notes for this inscription
            $notes = DB::table('notes')
                ->where('inscription_id', $inscription->id)
                ->where('type_session', 'normale')
                ->get();

            if ($notes->isEmpty()) {
                continue; // Skip if no notes
            }

            // Calculate average
            $totalNotes = 0;
            $count = 0;
            foreach ($notes as $note) {
                if ($note->note !== null) {
                    $totalNotes += $note->note;
                    $count++;
                }
            }

            if ($count == 0) {
                continue; // Skip if no valid notes
            }

            $moyenne = round($totalNotes / $count, 2);

            // Determine mention based on average
            $mention = null;
            if ($moyenne >= 16) {
                $mention = 'Excellent';
            } elseif ($moyenne >= 14) {
                $mention = 'Très Bien';
            } elseif ($moyenne >= 12) {
                $mention = 'Bien';
            } elseif ($moyenne >= 11) {
                $mention = 'Assez Bien';
            } elseif ($moyenne >= 10) {
                $mention = 'Passable';
            }

            // Determine decision
            // Admis if moyenne >= 10, ajourne if < 10
            $decision = $moyenne >= 10 ? 'admis' : 'ajourne';

            // Get the academic year to determine decision date
            $annee = DB::table('annees_universitaires')
                ->where('id', $inscription->annee_id)
                ->first();

            $dateDecision = $annee ? date('Y-m-d', strtotime($annee->date_fin . ' -15 days')) : now()->format('Y-m-d');

            $decisions[] = [
                'inscription_id' => $inscription->id,
                'moyenne_annuelle' => $moyenne,
                'mention' => $mention,
                'decision' => $decision,
                'date_decision' => $dateDecision,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert all decisions
        foreach ($decisions as $decision) {
            DB::table('decisions_annee')->insert($decision);
        }
    }
}