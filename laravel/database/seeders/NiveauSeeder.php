<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NiveauSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $niveaux = [
            // Cycle Préparatoire
            [
                'code_niveau' => 'CP1',
                'libelle' => 'Cycle Préparatoire - 1ère Année',
                'ordre' => 1,
            ],
            [
                'code_niveau' => 'CP2',
                'libelle' => 'Cycle Préparatoire - 2ème Année',
                'ordre' => 2,
            ],

            // Cycle Ingénieur
            [
                'code_niveau' => 'CI1',
                'libelle' => 'Cycle Ingénieur - 1ère Année',
                'ordre' => 3,
            ],
            [
                'code_niveau' => 'CI2',
                'libelle' => 'Cycle Ingénieur - 2ème Année',
                'ordre' => 4,
            ],
            [
                'code_niveau' => 'CI3',
                'libelle' => 'Cycle Ingénieur - 3ème Année',
                'ordre' => 5,
            ],

            // Master
            [
                'code_niveau' => 'M1',
                'libelle' => 'Master - 1ère Année',
                'ordre' => 6,
            ],
            [
                'code_niveau' => 'M2',
                'libelle' => 'Master - 2ème Année',
                'ordre' => 7,
            ],
        ];

        foreach ($niveaux as $niveau) {
            DB::table('niveaux')->insert(array_merge($niveau, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
