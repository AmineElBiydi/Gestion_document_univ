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
            // Modules Cycle Préparatoire
            ['code_module' => 'MATH101', 'nom_module' => 'Analyse Mathématique I', 'credits' => 6, 'description' => 'Fonctions, limites, dérivées et intégrales'],
            ['code_module' => 'MATH102', 'nom_module' => 'Algèbre Linéaire', 'credits' => 5, 'description' => 'Espaces vectoriels, matrices et systèmes linéaires'],
            ['code_module' => 'PHYS101', 'nom_module' => 'Physique I', 'credits' => 5, 'description' => 'Mécanique classique et thermodynamique'],
            ['code_module' => 'INFO101', 'nom_module' => 'Introduction à la Programmation', 'credits' => 5, 'description' => 'Algorithmique et programmation en C'],
            ['code_module' => 'LANG101', 'nom_module' => 'Anglais I', 'credits' => 2, 'description' => 'Communication en anglais technique'],
            ['code_module' => 'COMM101', 'nom_module' => 'Communication et Expression', 'credits' => 2, 'description' => 'Techniques de communication écrite et orale'],

            ['code_module' => 'MATH201', 'nom_module' => 'Analyse Mathématique II', 'credits' => 6, 'description' => 'Séries, équations différentielles et analyse vectorielle'],
            ['code_module' => 'MATH202', 'nom_module' => 'Probabilités et Statistiques', 'credits' => 4, 'description' => 'Théorie des probabilités et statistiques descriptives'],
            ['code_module' => 'PHYS201', 'nom_module' => 'Physique II', 'credits' => 5, 'description' => 'Électromagnétisme et optique'],
            ['code_module' => 'INFO201', 'nom_module' => 'Structures de Données', 'credits' => 5, 'description' => 'Listes, arbres, graphes et algorithmes'],
            ['code_module' => 'ELEC101', 'nom_module' => 'Électronique Fondamentale', 'credits' => 4, 'description' => 'Circuits électriques et composants électroniques'],
            ['code_module' => 'LANG201', 'nom_module' => 'Anglais II', 'credits' => 2, 'description' => 'Anglais technique avancé'],

            // Modules Génie Informatique
            ['code_module' => 'GI301', 'nom_module' => 'Programmation Orientée Objet', 'credits' => 5, 'description' => 'Concepts POO et programmation Java'],
            ['code_module' => 'GI302', 'nom_module' => 'Bases de Données', 'credits' => 5, 'description' => 'Modélisation et SQL'],
            ['code_module' => 'GI303', 'nom_module' => 'Systèmes d\'Exploitation', 'credits' => 4, 'description' => 'Architecture et gestion des ressources'],
            ['code_module' => 'GI304', 'nom_module' => 'Réseaux Informatiques', 'credits' => 4, 'description' => 'Protocoles TCP/IP et architecture réseau'],
            ['code_module' => 'GI305', 'nom_module' => 'Génie Logiciel', 'credits' => 4, 'description' => 'Méthodes de développement et UML'],
            ['code_module' => 'GI306', 'nom_module' => 'Architecture des Ordinateurs', 'credits' => 4, 'description' => 'Organisation et fonctionnement des processeurs'],

            ['code_module' => 'GI401', 'nom_module' => 'Développement Web', 'credits' => 5, 'description' => 'HTML, CSS, JavaScript et frameworks modernes'],
            ['code_module' => 'GI402', 'nom_module' => 'Intelligence Artificielle', 'credits' => 5, 'description' => 'Algorithmes d\'IA et apprentissage automatique'],
            ['code_module' => 'GI403', 'nom_module' => 'Sécurité Informatique', 'credits' => 4, 'description' => 'Cryptographie et sécurité des systèmes'],
            ['code_module' => 'GI404', 'nom_module' => 'Systèmes Distribués', 'credits' => 4, 'description' => 'Architecture et programmation distribuée'],
            ['code_module' => 'GI405', 'nom_module' => 'Gestion de Projet', 'credits' => 3, 'description' => 'Méthodologies agiles et gestion d\'équipe'],
            ['code_module' => 'GI406', 'nom_module' => 'Analyse de Données', 'credits' => 4, 'description' => 'Big Data et visualisation'],

            ['code_module' => 'GI501', 'nom_module' => 'Cloud Computing', 'credits' => 4, 'description' => 'Services cloud et déploiement'],
            ['code_module' => 'GI502', 'nom_module' => 'Développement Mobile', 'credits' => 5, 'description' => 'Applications iOS et Android'],
            ['code_module' => 'GI503', 'nom_module' => 'Blockchain et Cryptomonnaies', 'credits' => 3, 'description' => 'Technologies blockchain et applications'],
            ['code_module' => 'GI504', 'nom_module' => 'DevOps', 'credits' => 4, 'description' => 'CI/CD et automatisation'],
            ['code_module' => 'GI505', 'nom_module' => 'Projet de Fin d\'Études', 'credits' => 10, 'description' => 'Projet professionnel de synthèse'],

            // Modules Génie Électrique
            ['code_module' => 'GE301', 'nom_module' => 'Électronique Analogique', 'credits' => 5, 'description' => 'Amplificateurs et circuits analogiques'],
            ['code_module' => 'GE302', 'nom_module' => 'Électronique Numérique', 'credits' => 5, 'description' => 'Circuits logiques et microcontrôleurs'],
            ['code_module' => 'GE303', 'nom_module' => 'Traitement du Signal', 'credits' => 4, 'description' => 'Analyse et traitement des signaux'],
            ['code_module' => 'GE304', 'nom_module' => 'Automatique', 'credits' => 4, 'description' => 'Systèmes asservis et régulation'],
            ['code_module' => 'GE305', 'nom_module' => 'Machines Électriques', 'credits' => 5, 'description' => 'Moteurs et générateurs électriques'],

            // Modules Génie Mécanique
            ['code_module' => 'GM301', 'nom_module' => 'Mécanique des Solides', 'credits' => 5, 'description' => 'Résistance des matériaux et contraintes'],
            ['code_module' => 'GM302', 'nom_module' => 'Mécanique des Fluides', 'credits' => 4, 'description' => 'Dynamique des fluides et applications'],
            ['code_module' => 'GM303', 'nom_module' => 'Thermodynamique Appliquée', 'credits' => 4, 'description' => 'Cycles thermodynamiques et machines'],
            ['code_module' => 'GM304', 'nom_module' => 'CAO/DAO', 'credits' => 5, 'description' => 'Conception assistée par ordinateur'],
            ['code_module' => 'GM305', 'nom_module' => 'Fabrication Mécanique', 'credits' => 4, 'description' => 'Procédés d\'usinage et fabrication'],

            // Modules Génie Civil
            ['code_module' => 'GC301', 'nom_module' => 'Résistance des Matériaux', 'credits' => 5, 'description' => 'Calcul des structures et contraintes'],
            ['code_module' => 'GC302', 'nom_module' => 'Béton Armé', 'credits' => 5, 'description' => 'Conception et calcul des structures en béton'],
            ['code_module' => 'GC303', 'nom_module' => 'Mécanique des Sols', 'credits' => 4, 'description' => 'Géotechnique et fondations'],
            ['code_module' => 'GC304', 'nom_module' => 'Topographie', 'credits' => 4, 'description' => 'Levés topographiques et cartographie'],
            ['code_module' => 'GC305', 'nom_module' => 'Hydraulique', 'credits' => 4, 'description' => 'Écoulement et réseaux hydrauliques'],

            // Modules Big Data et IA (BDIA)
            ['code_module' => 'BDIA301', 'nom_module' => 'Fondements du Big Data', 'credits' => 5, 'description' => 'Introduction aux technologies Big Data et écosystème Hadoop'],
            ['code_module' => 'BDIA302', 'nom_module' => 'Machine Learning', 'credits' => 5, 'description' => 'Algorithmes d\'apprentissage supervisé et non supervisé'],
            ['code_module' => 'BDIA303', 'nom_module' => 'Deep Learning', 'credits' => 5, 'description' => 'Réseaux de neurones profonds et CNN'],
            ['code_module' => 'BDIA304', 'nom_module' => 'Data Mining', 'credits' => 4, 'description' => 'Extraction de connaissances et patterns'],
            ['code_module' => 'BDIA305', 'nom_module' => 'Traitement du Langage Naturel', 'credits' => 4, 'description' => 'NLP et analyse de texte'],
            ['code_module' => 'BDIA306', 'nom_module' => 'Visualisation de Données', 'credits' => 3, 'description' => 'Techniques de visualisation et storytelling'],

            ['code_module' => 'BDIA401', 'nom_module' => 'Big Data Analytics', 'credits' => 5, 'description' => 'Spark, Kafka et traitement en temps réel'],
            ['code_module' => 'BDIA402', 'nom_module' => 'IA Avancée', 'credits' => 5, 'description' => 'Reinforcement Learning et IA générative'],
            ['code_module' => 'BDIA403', 'nom_module' => 'Computer Vision', 'credits' => 4, 'description' => 'Traitement d\'images et reconnaissance'],
            ['code_module' => 'BDIA404', 'nom_module' => 'MLOps', 'credits' => 4, 'description' => 'Déploiement et monitoring de modèles ML'],
            ['code_module' => 'BDIA405', 'nom_module' => 'Éthique et IA', 'credits' => 3, 'description' => 'Aspects éthiques et responsabilité en IA'],

            // Modules Génie Système Embarqué et Cyber Security (GSECS)
            ['code_module' => 'GSECS301', 'nom_module' => 'Systèmes Embarqués', 'credits' => 5, 'description' => 'Architecture et programmation des systèmes embarqués'],
            ['code_module' => 'GSECS302', 'nom_module' => 'Microcontrôleurs et IoT', 'credits' => 5, 'description' => 'Arduino, ESP32 et Internet des Objets'],
            ['code_module' => 'GSECS303', 'nom_module' => 'Sécurité des Systèmes', 'credits' => 5, 'description' => 'Sécurité des systèmes d\'exploitation et réseaux'],
            ['code_module' => 'GSECS304', 'nom_module' => 'Cryptographie', 'credits' => 4, 'description' => 'Algorithmes cryptographiques et PKI'],
            ['code_module' => 'GSECS305', 'nom_module' => 'RTOS', 'credits' => 4, 'description' => 'Systèmes d\'exploitation temps réel'],

            ['code_module' => 'GSECS401', 'nom_module' => 'Cybersécurité Avancée', 'credits' => 5, 'description' => 'Ethical hacking et tests de pénétration'],
            ['code_module' => 'GSECS402', 'nom_module' => 'Sécurité IoT', 'credits' => 4, 'description' => 'Sécurisation des objets connectés'],
            ['code_module' => 'GSECS403', 'nom_module' => 'Forensique Numérique', 'credits' => 4, 'description' => 'Analyse et investigation numérique'],
            ['code_module' => 'GSECS404', 'nom_module' => 'Systèmes Critiques', 'credits' => 4, 'description' => 'Conception de systèmes à haute fiabilité'],
            ['code_module' => 'GSECS405', 'nom_module' => 'Blockchain et Sécurité', 'credits' => 3, 'description' => 'Applications blockchain en cybersécurité'],

            // Modules Supply Chain Management (SCM)
            ['code_module' => 'SCM301', 'nom_module' => 'Gestion de la Chaîne Logistique', 'credits' => 5, 'description' => 'Principes et stratégies SCM'],
            ['code_module' => 'SCM302', 'nom_module' => 'Logistique et Transport', 'credits' => 5, 'description' => 'Gestion des flux et optimisation'],
            ['code_module' => 'SCM303', 'nom_module' => 'Gestion des Stocks', 'credits' => 4, 'description' => 'Méthodes de gestion et prévision'],
            ['code_module' => 'SCM304', 'nom_module' => 'Achats et Approvisionnement', 'credits' => 4, 'description' => 'Stratégies d\'achat et négociation'],
            ['code_module' => 'SCM305', 'nom_module' => 'ERP et Systèmes d\'Information', 'credits' => 4, 'description' => 'SAP, Oracle et gestion intégrée'],

            ['code_module' => 'SCM401', 'nom_module' => 'Supply Chain Analytics', 'credits' => 5, 'description' => 'Analyse de données et aide à la décision'],
            ['code_module' => 'SCM402', 'nom_module' => 'Lean et Six Sigma', 'credits' => 4, 'description' => 'Amélioration continue et qualité'],
            ['code_module' => 'SCM403', 'nom_module' => 'E-Logistique', 'credits' => 4, 'description' => 'Commerce électronique et logistique digitale'],
            ['code_module' => 'SCM404', 'nom_module' => 'Supply Chain Durable', 'credits' => 3, 'description' => 'Développement durable et RSE'],
            ['code_module' => 'SCM405', 'nom_module' => 'Gestion de Projet SCM', 'credits' => 4, 'description' => 'Pilotage de projets logistiques'],
        ];

        foreach ($modules as $module) {
            DB::table('modules')->insert(array_merge($module, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
