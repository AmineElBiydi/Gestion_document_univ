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

        // Cycle Préparatoire - CP1
        $cpModules = [
            ['MATH101', 2.0],
            ['MATH102', 1.5],
            ['PHYS101', 1.5],
            ['INFO101', 1.5],
            ['LANG101', 1.0],
            ['COMM101', 1.0]
        ];
        foreach ($cpModules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['CP']->id,
                'niveau_id' => $niveaux['CP1']->id,
                'coefficient' => $coef,
                'est_obligatoire' => true,
            ];
        }

        // Cycle Préparatoire - CP2
        $cp2Modules = [
            ['MATH201', 2.0],
            ['MATH202', 1.5],
            ['PHYS201', 1.5],
            ['INFO201', 1.5],
            ['ELEC101', 1.5],
            ['LANG201', 1.0]
        ];
        foreach ($cp2Modules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['CP']->id,
                'niveau_id' => $niveaux['CP2']->id,
                'coefficient' => $coef,
                'est_obligatoire' => true,
            ];
        }

        // Génie Informatique - CI1
        $giCI1Modules = [
            ['GI301', 2.0],
            ['GI302', 2.0],
            ['GI303', 1.5],
            ['GI304', 1.5],
            ['GI305', 1.5],
            ['GI306', 1.5]
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

        // Génie Informatique - CI2
        $giCI2Modules = [
            ['GI401', 2.0],
            ['GI402', 2.0],
            ['GI403', 1.5],
            ['GI404', 1.5],
            ['GI405', 1.0],
            ['GI406', 1.5]
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

        // Génie Informatique - CI3
        $giCI3Modules = [
            ['GI501', 1.5],
            ['GI502', 2.0],
            ['GI503', 1.0],
            ['GI504', 1.5],
            ['GI505', 3.0]
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

        // Génie Système Embarqué et Cyber Security - CI1
        $gsecsCI1Modules = [
            ['GSECS301', 2.0],
            ['GSECS302', 2.0],
            ['GSECS303', 2.0],
            ['GSECS304', 1.5],
            ['GSECS305', 1.5]
        ];
        foreach ($gsecsCI1Modules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['GSECS']->id,
                'niveau_id' => $niveaux['CI1']->id,
                'coefficient' => $coef,
                'est_obligatoire' => true,
            ];
        }

        // Génie Mécatronique - CI1
        $gmCI1Modules = [
            ['GM301', 2.0],
            ['GM302', 1.5],
            ['GM303', 1.5],
            ['GM304', 2.0],
            ['GM305', 1.5]
        ];
        foreach ($gmCI1Modules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['GM']->id,
                'niveau_id' => $niveaux['CI1']->id,
                'coefficient' => $coef,
                'est_obligatoire' => true,
            ];
        }

        // Génie Civil - CI1
        $gcCI1Modules = [
            ['GC301', 2.0],
            ['GC302', 2.0],
            ['GC303', 1.5],
            ['GC304', 1.5],
            ['GC305', 1.5]
        ];
        foreach ($gcCI1Modules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['GC']->id,
                'niveau_id' => $niveaux['CI1']->id,
                'coefficient' => $coef,
                'est_obligatoire' => true,
            ];
        }

        // Big Data et IA - CI1
        $bdiaCI1Modules = [
            ['BDIA301', 2.0],
            ['BDIA302', 2.0],
            ['BDIA303', 2.0],
            ['BDIA304', 1.5],
            ['BDIA305', 1.5],
            ['BDIA306', 1.0]
        ];
        foreach ($bdiaCI1Modules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['BDIA']->id,
                'niveau_id' => $niveaux['CI1']->id,
                'coefficient' => $coef,
                'est_obligatoire' => true,
            ];
        }

        // Big Data et IA - CI2
        $bdiaCI2Modules = [
            ['BDIA401', 2.0],
            ['BDIA402', 2.0],
            ['BDIA403', 1.5],
            ['BDIA404', 1.5],
            ['BDIA405', 1.0]
        ];
        foreach ($bdiaCI2Modules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['BDIA']->id,
                'niveau_id' => $niveaux['CI2']->id,
                'coefficient' => $coef,
                'est_obligatoire' => true,
            ];
        }

        // Supply Chain Management - CI1
        $scmCI1Modules = [
            ['SCM301', 2.0],
            ['SCM302', 2.0],
            ['SCM303', 1.5],
            ['SCM304', 1.5],
            ['SCM305', 1.5]
        ];
        foreach ($scmCI1Modules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['SCM']->id,
                'niveau_id' => $niveaux['CI1']->id,
                'coefficient' => $coef,
                'est_obligatoire' => true,
            ];
        }

        // Supply Chain Management - CI2
        $scmCI2Modules = [
            ['SCM401', 2.0],
            ['SCM402', 1.5],
            ['SCM403', 1.5],
            ['SCM404', 1.0],
            ['SCM405', 1.5]
        ];
        foreach ($scmCI2Modules as [$code, $coef]) {
            $modulesNiveau[] = [
                'module_id' => $modules[$code]->id,
                'filiere_id' => $filieres['SCM']->id,
                'niveau_id' => $niveaux['CI2']->id,
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
