<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
                // Base data - must be seeded first
            AnneeUniversitaireSeeder::class,
            FiliereSeeder::class,
            NiveauSeeder::class,
            ModuleSeeder::class,
            ProfesseurSeeder::class,

                // Relationship tables
            ModuleNiveauSeeder::class,
            ProfesseurFiliereSeeder::class,

                // Users and enrollments
            AdminSeeder::class,
            EtudiantSeeder::class,
            InscriptionSeeder::class,
            NoteSeeder::class,

        ]);
    }
}
