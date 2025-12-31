<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get IDs for reference
        $etudiants = DB::table('etudiants')->get()->keyBy('apogee');
        $inscriptions = DB::table('inscriptions')->get();
        $modulesNiveau = DB::table('modules_niveau')->get();
        $modules = DB::table('modules')->get()->keyBy('code_module');
        $niveaux = DB::table('niveaux')->get()->keyBy('code_niveau');
        $filieres = DB::table('filieres')->get()->keyBy('code_filiere');
        $annees = DB::table('annees_universitaires')->get()->keyBy('libelle');

        $notes = [];

        // Helper function to get inscription ID
        $getInscriptionId = function ($apogee, $anneeLibelle, $niveauCode) use ($etudiants, $inscriptions, $annees, $niveaux) {
            $etudiantId = $etudiants[$apogee]->id;
            $anneeId = $annees[$anneeLibelle]->id;
            $niveauId = $niveaux[$niveauCode]->id;

            foreach ($inscriptions as $inscription) {
                if (
                    $inscription->etudiant_id == $etudiantId &&
                    $inscription->annee_id == $anneeId &&
                    $inscription->niveau_id == $niveauId
                ) {
                    return $inscription->id;
                }
            }
            return null;
        };

        // Helper function to get module_niveau_id
        $getModuleNiveauId = function ($moduleCode, $niveauCode, $filiereCode) use ($modulesNiveau, $modules, $niveaux, $filieres) {
            $moduleId = $modules[$moduleCode]->id;
            $niveauId = $niveaux[$niveauCode]->id;
            $filiereId = $filieres[$filiereCode]->id;

            foreach ($modulesNiveau as $mn) {
                if (
                    $mn->module_id == $moduleId &&
                    $mn->niveau_id == $niveauId &&
                    $mn->filiere_id == $filiereId
                ) {
                    return $mn->id;
                }
            }
            return null;
        };

        // ========== ISMAIL LYAMANI (22050001) ==========

        // 2AP1 - 2022-2023 (Semestres 1 et 2)
        $ismailCP1 = $getInscriptionId('22050001', '2022-2023', 'CP1');
        $cp1Modules = ['ANL1', 'ALG1', 'PHY1', 'MECA1', 'INFO1', 'LC1', 'ANL2', 'ALG2', 'PHY2', 'CHIM', 'MAO', 'LC2'];
        $ismailCP1Grades = [15.83, 7, 12.667, 10, 13.95, 13.75, 13.05, 15.5, 15.5, 17.25, 15.72, 14];

        foreach ($cp1Modules as $index => $moduleCode) {
            $moduleNiveauId = $getModuleNiveauId($moduleCode, 'CP1', '2AP');
            if ($moduleNiveauId && $ismailCP1) {
                $notes[] = [
                    'inscription_id' => $ismailCP1,
                    'module_niveau_id' => $moduleNiveauId,
                    'type_session' => 'normale',
                    'note' => $ismailCP1Grades[$index],
                    'est_valide' => true,
                    'date_saisie' => '2023-07-15',
                ];
            }
        }

        // 2AP2 - 2023-2024 (Semestres 3 et 4)
        $ismailCP2 = $getInscriptionId('22050001', '2023-2024', 'CP2');
        $cp2Modules = ['ALG3', 'ANL3', 'PHY3', 'MECA3', 'INFO2', 'LC3', 'ANL4', 'MAPP', 'PHY4', 'ELEC', 'MGMT', 'LC4'];
        $ismailCP2Grades = [13.5, 12.8, 13, 14.67, 19, 16.375, 13.75, 12.84, 11, 10, 15.8, 15.25];

        foreach ($cp2Modules as $index => $moduleCode) {
            $moduleNiveauId = $getModuleNiveauId($moduleCode, 'CP2', '2AP');
            if ($moduleNiveauId && $ismailCP2) {
                $notes[] = [
                    'inscription_id' => $ismailCP2,
                    'module_niveau_id' => $moduleNiveauId,
                    'type_session' => 'normale',
                    'note' => $ismailCP2Grades[$index],
                    'est_valide' => true,
                    'date_saisie' => '2024-07-15',
                ];
            }
        }

        // GI1 - 2024-2025 (Semestres 5 et 6)
        $ismailCI1 = $getInscriptionId('22050001', '2024-2025', 'CI1');
        $ci1Modules = ['TGRO', 'ARCHI', 'BDR', 'RESX1', 'SDC', 'LE1', 'DSKL', 'SYSEXP', 'MOO', 'TLC', 'DEVWEB', 'POOJAVA', 'LE2', 'CASS'];
        $ismailCI1Grades = [15.63, 15.9, 16.825, 16.55, 16.16, 15.5, 17, 14.8, 14.55, 14, 18, 15.75, 14.07, 15];

        foreach ($ci1Modules as $index => $moduleCode) {
            $moduleNiveauId = $getModuleNiveauId($moduleCode, 'CI1', 'GI');
            if ($moduleNiveauId && $ismailCI1) {
                $notes[] = [
                    'inscription_id' => $ismailCI1,
                    'module_niveau_id' => $moduleNiveauId,
                    'type_session' => 'normale',
                    'note' => $ismailCI1Grades[$index],
                    'est_valide' => true,
                    'date_saisie' => '2025-07-15',
                ];
            }
        }

        // ========== ABDELLATIF OUMHELLA (22050002) ==========

        // 2AP1 - 2022-2023
        $abdellatifCP1 = $getInscriptionId('22050002', '2022-2023', 'CP1');
        $abdellatifCP1Grades = [14.5, 12.3, 13.8, 11.5, 15.2, 14.0, 12.8, 16.0, 14.5, 15.8, 14.9, 13.5];

        foreach ($cp1Modules as $index => $moduleCode) {
            $moduleNiveauId = $getModuleNiveauId($moduleCode, 'CP1', '2AP');
            if ($moduleNiveauId && $abdellatifCP1) {
                $notes[] = [
                    'inscription_id' => $abdellatifCP1,
                    'module_niveau_id' => $moduleNiveauId,
                    'type_session' => 'normale',
                    'note' => $abdellatifCP1Grades[$index],
                    'est_valide' => true,
                    'date_saisie' => '2023-07-15',
                ];
            }
        }

        // 2AP2 - 2023-2024
        $abdellatifCP2 = $getInscriptionId('22050002', '2023-2024', 'CP2');
        $abdellatifCP2Grades = [14.2, 13.5, 12.9, 15.1, 17.5, 15.8, 14.3, 13.2, 12.5, 11.8, 16.2, 14.9];

        foreach ($cp2Modules as $index => $moduleCode) {
            $moduleNiveauId = $getModuleNiveauId($moduleCode, 'CP2', '2AP');
            if ($moduleNiveauId && $abdellatifCP2) {
                $notes[] = [
                    'inscription_id' => $abdellatifCP2,
                    'module_niveau_id' => $moduleNiveauId,
                    'type_session' => 'normale',
                    'note' => $abdellatifCP2Grades[$index],
                    'est_valide' => true,
                    'date_saisie' => '2024-07-15',
                ];
            }
        }

        // GI1 - 2024-2025
        $abdellatifCI1 = $getInscriptionId('22050002', '2024-2025', 'CI1');
        $abdellatifCI1Grades = [15.2, 16.1, 15.9, 15.7, 16.3, 14.8, 16.5, 15.0, 14.2, 13.8, 17.2, 16.0, 13.9, 14.5];

        foreach ($ci1Modules as $index => $moduleCode) {
            $moduleNiveauId = $getModuleNiveauId($moduleCode, 'CI1', 'GI');
            if ($moduleNiveauId && $abdellatifCI1) {
                $notes[] = [
                    'inscription_id' => $abdellatifCI1,
                    'module_niveau_id' => $moduleNiveauId,
                    'type_session' => 'normale',
                    'note' => $abdellatifCI1Grades[$index],
                    'est_valide' => true,
                    'date_saisie' => '2025-07-15',
                ];
            }
        }

        // ========== AMINE EL BIYADI (22050003) ==========

        // 2AP1 - 2022-2023
        $amineCP1 = $getInscriptionId('22050003', '2022-2023', 'CP1');
        $amineCP1Grades = [13.8, 11.5, 14.2, 12.0, 14.5, 13.2, 13.5, 15.8, 14.0, 16.5, 15.0, 13.8];

        foreach ($cp1Modules as $index => $moduleCode) {
            $moduleNiveauId = $getModuleNiveauId($moduleCode, 'CP1', '2AP');
            if ($moduleNiveauId && $amineCP1) {
                $notes[] = [
                    'inscription_id' => $amineCP1,
                    'module_niveau_id' => $moduleNiveauId,
                    'type_session' => 'normale',
                    'note' => $amineCP1Grades[$index],
                    'est_valide' => true,
                    'date_saisie' => '2023-07-15',
                ];
            }
        }

        // 2AP2 - 2023-2024
        $amineCP2 = $getInscriptionId('22050003', '2023-2024', 'CP2');
        $amineCP2Grades = [13.8, 13.2, 12.5, 14.8, 18.0, 16.0, 14.0, 12.5, 11.5, 10.5, 15.5, 15.0];

        foreach ($cp2Modules as $index => $moduleCode) {
            $moduleNiveauId = $getModuleNiveauId($moduleCode, 'CP2', '2AP');
            if ($moduleNiveauId && $amineCP2) {
                $notes[] = [
                    'inscription_id' => $amineCP2,
                    'module_niveau_id' => $moduleNiveauId,
                    'type_session' => 'normale',
                    'note' => $amineCP2Grades[$index],
                    'est_valide' => true,
                    'date_saisie' => '2024-07-15',
                ];
            }
        }

        // GI1 - 2024-2025
        $amineCI1 = $getInscriptionId('22050003', '2024-2025', 'CI1');
        $amineCI1Grades = [15.0, 15.5, 16.2, 16.0, 15.8, 15.0, 16.8, 14.5, 14.0, 13.5, 17.5, 15.5, 13.8, 14.8];

        foreach ($ci1Modules as $index => $moduleCode) {
            $moduleNiveauId = $getModuleNiveauId($moduleCode, 'CI1', 'GI');
            if ($moduleNiveauId && $amineCI1) {
                $notes[] = [
                    'inscription_id' => $amineCI1,
                    'module_niveau_id' => $moduleNiveauId,
                    'type_session' => 'normale',
                    'note' => $amineCI1Grades[$index],
                    'est_valide' => true,
                    'date_saisie' => '2025-07-15',
                ];
            }
        }

        // Insert all notes
        foreach ($notes as $note) {
            DB::table('notes')->insert(array_merge($note, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}