<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleNiveauSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get IDs for reference
        $modules = DB::table('modules')->get()->keyBy('code_module');
        $filieres = DB::table('filieres')->get()->keyBy('code_filiere');
        $niveaux = DB::table('niveaux')->get()->keyBy('code_niveau');

        $modulesNiveau = [];

        // Cycle Préparatoire - CP1 (Semestres 1 et 2)
        $cpModules = [
            // Semestre 1
            ['ANL1', 1.0],
            ['ALG1', 1.0],
            ['PHY1', 1.0],
            ['MECA1', 1.0],
            ['INFO1', 1.0],
            ['LC1', 1.0],
            // Semestre 2
            ['ANL2', 1.0],
            ['ALG2', 1.0],
            ['PHY2', 1.0],
            ['CHIM', 1.0],
            ['MAO', 1.0],
            ['LC2', 1.0]
        ];
        foreach ($cpModules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['2AP']->id,
                'niveau_id' => $niveaux['CP1']->id,
                'coefficient' => $coef,
                'est_obligatoire' => true,
            ];
        }

        // Cycle Préparatoire - CP2 (Semestres 3 et 4)
        $cp2Modules = [
            // Semestre 3
            ['ALG3', 1.0],
            ['ANL3', 1.0],
            ['PHY3', 1.0],
            ['MECA3', 1.0],
            ['INFO2', 1.0],
            ['LC3', 1.0],
            // Semestre 4
            ['ANL4', 1.0],
            ['MAPP', 1.0],
            ['PHY4', 1.0],
            ['ELEC', 1.0],
            ['MGMT', 1.0],
            ['LC4', 1.0]
        ];
        foreach ($cp2Modules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['2AP']->id,
                'niveau_id' => $niveaux['CP2']->id,
                'coefficient' => $coef,
                'est_obligatoire' => true,
            ];
        }

        // Génie Informatique - CI1 (Semestres 5 et 6)
        $giCI1Modules = [
            // Semestre 5
            ['TGRO', 1.0],
            ['ARCHI', 1.0],
            ['BDR', 1.0],
            ['RESX1', 1.0],
            ['SDC', 1.0],
            ['LE1', 1.0],
            // Semestre 6
            ['DSKL', 1.0],
            ['SYSEXP', 1.0],
            ['MOO', 1.0],
            ['TLC', 1.0],
            ['DEVWEB', 1.0],
            ['POOJAVA', 1.0],
            ['LE2', 1.0],
            ['CASS', 1.0]
        ];
        foreach ($giCI1Modules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['GI']->id,
                'niveau_id' => $niveaux['CI1']->id,
                'coefficient' => $coef,
                'est_obligatoire' => true,
            ];
        }

        // Génie Informatique - CI2 (Semestres 7 et 8)
        $giCI2Modules = [
            // Semestre 7
            ['ABDR', 1.0],
            ['DEVWEBA', 1.0],
            ['RESXA', 1.0],
            ['MGL', 1.0],
            ['DOTNET', 1.0],
            ['LE3', 1.0],
            ['PI', 1.0],
            // Semestre 8
            ['ML', 1.0],
            ['ASSS', 1.0],
            ['RESX2', 1.0],
            ['JEE', 1.0],
            ['MSDM', 1.0],
            ['LE4', 1.0],
            ['GPE', 1.0]
        ];
        foreach ($giCI2Modules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['GI']->id,
                'niveau_id' => $niveaux['CI2']->id,
                'coefficient' => $coef,
                'est_obligatoire' => true,
            ];
        }

        // Génie Informatique - CI3 (Semestre 9)
        $giCI3Modules = [
            ['FTW', 1.0],
            ['BDA', 1.0],
            ['ERP', 1.0],
            ['USI', 1.0],
            ['DL', 1.0],
            ['LE5', 1.0],
            ['EMPSKL', 1.0]
        ];
        foreach ($giCI3Modules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['GI']->id,
                'niveau_id' => $niveaux['CI3']->id,
                'coefficient' => $coef,
                'est_obligatoire' => true,
            ];
        }

        // Insert all module-niveau associations
        foreach ($modulesNiveau as $mn) {
            DB::table('modules_niveau')->insert(array_merge($mn, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}