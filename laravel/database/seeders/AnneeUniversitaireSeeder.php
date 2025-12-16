<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnneeUniversitaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anneesUniversitaires = [
            [
                'libelle' => '2022-2023',
                'date_debut' => '2022-09-01',
                'date_fin' => '2023-07-31',
                'est_active' => false,
            ],
            [
                'libelle' => '2023-2024',
                'date_debut' => '2023-09-01',
                'date_fin' => '2024-07-31',
                'est_active' => false,
            ],
            [
                'libelle' => '2024-2025',
                'date_debut' => '2024-09-01',
                'date_fin' => '2025-07-31',
                'est_active' => true,
            ],
            [
                'libelle' => '2025-2026',
                'date_debut' => '2025-09-01',
                'date_fin' => '2026-07-31',
                'est_active' => false,
            ],
        ];

        foreach ($anneesUniversitaires as $annee) {
            DB::table('annees_universitaires')->insert(array_merge($annee, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
