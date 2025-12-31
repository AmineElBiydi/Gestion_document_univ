<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            // Semestre 1 - Cycle Préparatoire
            ['code_module' => 'ANL1', 'nom_module' => 'Analyse 1', 'credits' => 1, 'description' => 'Fonctions, limites, dérivées et intégrales'],
            ['code_module' => 'ALG1', 'nom_module' => 'Algèbre 1', 'credits' => 1, 'description' => 'Espaces vectoriels, matrices et systèmes linéaires'],
            ['code_module' => 'PHY1', 'nom_module' => 'Physique 1', 'credits' => 1, 'description' => 'Mécanique classique et cinématique'],
            ['code_module' => 'MECA1', 'nom_module' => 'Mécanique 1', 'credits' => 1, 'description' => 'Statique et dynamique des systèmes'],
            ['code_module' => 'INFO1', 'nom_module' => 'Informatique I', 'credits' => 1, 'description' => 'Algorithmique et programmation en C'],
            ['code_module' => 'LC1', 'nom_module' => 'Language et Communication 1', 'credits' => 1, 'description' => 'Techniques de communication écrite et orale'],

            // Semestre 2 - Cycle Préparatoire
            ['code_module' => 'ANL2', 'nom_module' => 'Analyse 2', 'credits' => 1, 'description' => 'Séries, intégrales multiples et équations différentielles'],
            ['code_module' => 'ALG2', 'nom_module' => 'Algèbre 2', 'credits' => 1, 'description' => 'Algèbre linéaire avancée et diagonalisation'],
            ['code_module' => 'PHY2', 'nom_module' => 'Physique 2', 'credits' => 1, 'description' => 'Électromagnétisme et optique'],
            ['code_module' => 'CHIM', 'nom_module' => 'Chimie', 'credits' => 1, 'description' => 'Chimie générale et organique'],
            ['code_module' => 'MAO', 'nom_module' => 'Mathématique assistés par Ordinateur', 'credits' => 1, 'description' => 'Calcul numérique et outils informatiques'],
            ['code_module' => 'LC2', 'nom_module' => 'Language et Communication 2', 'credits' => 1, 'description' => 'Communication professionnelle et rédaction'],

            // Semestre 3 - Cycle Préparatoire
            ['code_module' => 'ALG3', 'nom_module' => 'Algèbre 3', 'credits' => 1, 'description' => 'Structures algébriques et applications'],
            ['code_module' => 'ANL3', 'nom_module' => 'Analyse 3', 'credits' => 1, 'description' => 'Analyse complexe et transformées'],
            ['code_module' => 'PHY3', 'nom_module' => 'Physique 3', 'credits' => 1, 'description' => 'Thermodynamique et physique statistique'],
            ['code_module' => 'MECA3', 'nom_module' => 'Mécanique 3', 'credits' => 1, 'description' => 'Mécanique des fluides et résistance des matériaux'],
            ['code_module' => 'INFO2', 'nom_module' => 'Informatique 2', 'credits' => 1, 'description' => 'Programmation orientée objet et structures de données'],
            ['code_module' => 'LC3', 'nom_module' => 'Language et Communication 3', 'credits' => 1, 'description' => 'Communication interculturelle et anglais technique'],

            // Semestre 4 - Cycle Préparatoire
            ['code_module' => 'ANL4', 'nom_module' => 'Analyse 4', 'credits' => 1, 'description' => 'Analyse fonctionnelle et applications'],
            ['code_module' => 'MAPP', 'nom_module' => 'Mathématiques appliquées', 'credits' => 1, 'description' => 'Probabilités, statistiques et optimisation'],
            ['code_module' => 'PHY4', 'nom_module' => 'Physique 4', 'credits' => 1, 'description' => 'Physique quantique et physique moderne'],
            ['code_module' => 'ELEC', 'nom_module' => 'Électronique', 'credits' => 1, 'description' => 'Circuits électroniques et systèmes numériques'],
            ['code_module' => 'MGMT', 'nom_module' => 'Management', 'credits' => 1, 'description' => 'Gestion de projet et management des organisations'],
            ['code_module' => 'LC4', 'nom_module' => 'Language et Communication 4', 'credits' => 1, 'description' => 'Présentation professionnelle et communication d\'entreprise'],

            // Semestre 5 - Génie Informatique
            ['code_module' => 'TGRO', 'nom_module' => 'Théorie des Graphes et Recherche Opérationnel', 'credits' => 1, 'description' => 'Théorie des graphes, optimisation et recherche opérationnelle'],
            ['code_module' => 'ARCHI', 'nom_module' => 'Architecture des Ordinateurs & Assembleur', 'credits' => 1, 'description' => 'Architecture matérielle et programmation assembleur'],
            ['code_module' => 'BDR', 'nom_module' => 'Base des Donnees Relationnelles', 'credits' => 1, 'description' => 'Conception et gestion de bases de données relationnelles'],
            ['code_module' => 'RESX1', 'nom_module' => 'Réseaux Informatiques', 'credits' => 1, 'description' => 'Fondamentaux des réseaux et protocoles TCP/IP'],
            ['code_module' => 'SDC', 'nom_module' => 'Structure de Données en C', 'credits' => 1, 'description' => 'Structures de données avancées et algorithmes en C'],
            ['code_module' => 'LE1', 'nom_module' => 'Langues étrangères 1', 'credits' => 1, 'description' => 'Anglais technique et communication professionnelle'],

            // Semestre 6 - Génie Informatique
            ['code_module' => 'DSKL', 'nom_module' => 'Digital Skills', 'credits' => 1, 'description' => 'Compétences numériques et outils digitaux'],
            ['code_module' => 'SYSEXP', 'nom_module' => 'Systèmes d\'Exploitation et Linux', 'credits' => 1, 'description' => 'Administration système et commandes Linux'],
            ['code_module' => 'MOO', 'nom_module' => 'Modelisation Orientée Objet', 'credits' => 1, 'description' => 'UML et conception orientée objet'],
            ['code_module' => 'TLC', 'nom_module' => 'Théories des Langages et Compilation', 'credits' => 1, 'description' => 'Théorie des langages formels et compilation'],
            ['code_module' => 'DEVWEB', 'nom_module' => 'Développement Web', 'credits' => 1, 'description' => 'HTML, CSS, JavaScript et développement web frontend'],
            ['code_module' => 'POOJAVA', 'nom_module' => 'Programmation Orientée Objet Java', 'credits' => 1, 'description' => 'POO avancée avec Java'],
            ['code_module' => 'LE2', 'nom_module' => 'Langues étrangères 2', 'credits' => 1, 'description' => 'Anglais professionnel et communication interculturelle'],
            ['code_module' => 'CASS', 'nom_module' => 'Culture & Arts & Sport Skills', 'credits' => 1, 'description' => 'Développement personnel et compétences culturelles'],

            // Semestre 7 - Génie Informatique
            ['code_module' => 'ABDR', 'nom_module' => 'Administration des Bases de Donnees Relationnelles', 'credits' => 1, 'description' => 'Administration avancée de SGBD et optimisation'],
            ['code_module' => 'DEVWEBA', 'nom_module' => 'Developpement Web Avance', 'credits' => 1, 'description' => 'Frameworks web modernes et architectures avancées'],
            ['code_module' => 'RESXA', 'nom_module' => 'Reseaux Informatiques Avances', 'credits' => 1, 'description' => 'Réseaux avancés, sécurité et administration'],
            ['code_module' => 'MGL', 'nom_module' => 'Méthodologies et Génie Logiciel', 'credits' => 1, 'description' => 'Méthodes agiles, SCRUM et gestion de projets logiciels'],
            ['code_module' => 'DOTNET', 'nom_module' => 'Technologie DotNet', 'credits' => 1, 'description' => 'Développement avec .NET Framework et C#'],
            ['code_module' => 'LE3', 'nom_module' => 'Langues etrangères 3', 'credits' => 1, 'description' => 'Anglais des affaires et rédaction technique'],
            ['code_module' => 'PI', 'nom_module' => 'Proprieté Intellectuelle', 'credits' => 1, 'description' => 'Droit de la propriété intellectuelle et brevets'],

            // Semestre 8 - Génie Informatique
            ['code_module' => 'ML', 'nom_module' => 'Machine Learning', 'credits' => 1, 'description' => 'Apprentissage automatique et algorithmes de ML'],
            ['code_module' => 'ASSS', 'nom_module' => 'Administration Systèmes, Services et Sécurité', 'credits' => 1, 'description' => 'Administration système avancée et cybersécurité'],
            ['code_module' => 'RESX2', 'nom_module' => 'Réseaux', 'credits' => 1, 'description' => 'Architecture réseau et technologies émergentes'],
            ['code_module' => 'JEE', 'nom_module' => 'Java Entreprise Edition', 'credits' => 1, 'description' => 'Développement d\'applications d\'entreprise avec JEE'],
            ['code_module' => 'MSDM', 'nom_module' => 'Microservices et Developpement Mobile', 'credits' => 1, 'description' => 'Architecture microservices et applications mobiles'],
            ['code_module' => 'LE4', 'nom_module' => 'Langues étrangères 4', 'credits' => 1, 'description' => 'Communication professionnelle avancée en anglais'],
            ['code_module' => 'GPE', 'nom_module' => 'Gestion de projet et entreprise', 'credits' => 1, 'description' => 'Management de projet et entrepreneuriat'],

            // Semestre 9 - Génie Informatique
            ['code_module' => 'FTW', 'nom_module' => 'Frameworks Technologie Web', 'credits' => 1, 'description' => 'Frameworks web modernes (React, Angular, Vue.js)'],
            ['code_module' => 'BDA', 'nom_module' => 'Big Data & Analytics', 'credits' => 1, 'description' => 'Technologies Big Data et analyse de données massives'],
            ['code_module' => 'ERP', 'nom_module' => 'Systèmes de Planification des Ressources d\'Entreprise (ERP)', 'credits' => 1, 'description' => 'Systèmes ERP et gestion intégrée'],
            ['code_module' => 'USI', 'nom_module' => 'Urbanisme des Systemes d\'Information', 'credits' => 1, 'description' => 'Architecture et urbanisation des SI'],
            ['code_module' => 'DL', 'nom_module' => 'Deep Learning', 'credits' => 1, 'description' => 'Réseaux de neurones profonds et apprentissage profond'],
            ['code_module' => 'LE5', 'nom_module' => 'Langues étrangères 5', 'credits' => 1, 'description' => 'Anglais des affaires et négociation'],
            ['code_module' => 'EMPSKL', 'nom_module' => 'Employment skills', 'credits' => 1, 'description' => 'Compétences professionnelles et employabilité'],

        ];

        foreach ($modules as $module) {
            DB::table('modules')->insert(array_merge($module, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}