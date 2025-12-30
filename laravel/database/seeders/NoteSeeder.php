<?php

namespace Database\Seeders;

use App\Models\Inscription;
use App\Models\ModuleNiveau;
use App\Models\Note;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all inscriptions
        $inscriptions = Inscription::all();

        foreach ($inscriptions as $inscription) {
            // Find modules for the student's level and field
            $modulesNiveau = ModuleNiveau::where('filiere_id', $inscription->filiere_id)
                ->where('niveau_id', $inscription->niveau_id)
                ->get();

            foreach ($modulesNiveau as $moduleNiveau) {
                // Generate a random note
                $noteVal = rand(0, 2000) / 100; // Random float 0.00 to 20.00
                $isValid = $noteVal >= 10;

                // Check if note already exists to avoid duplicates (based on unique constraint)
                $exists = Note::where('inscription_id', $inscription->id)
                    ->where('module_niveau_id', $moduleNiveau->id)
                    ->where('type_session', 'normale')
                    ->exists();

                if (!$exists) {
                    Note::create([
                        'inscription_id' => $inscription->id,
                        'module_niveau_id' => $moduleNiveau->id,
                        'type_session' => 'normale',
                        'note' => $noteVal,
                        'est_valide' => $isValid,
                        'date_saisie' => now(),
                    ]);
                }

                // Optionally add rattrapage if not valid
                if (!$isValid && rand(0, 1)) {
                     $noteRattrapage = rand(0, 2000) / 100;
                     $isRattrapageValid = $noteRattrapage >= 10;
                     
                     $existsRattrapage = Note::where('inscription_id', $inscription->id)
                        ->where('module_niveau_id', $moduleNiveau->id)
                        ->where('type_session', 'rattrapage')
                        ->exists();
                    
                     if (!$existsRattrapage) {
                         Note::create([
                            'inscription_id' => $inscription->id,
                            'module_niveau_id' => $moduleNiveau->id,
                            'type_session' => 'rattrapage',
                            'note' => $noteRattrapage,
                            'est_valide' => $isRattrapageValid,
                            'date_saisie' => now(),
                        ]);
                     }
                }
            }
        }
    }
}
