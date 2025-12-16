<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 20mm 25mm; }
        body { 
            font-family: 'DejaVu Sans', 'Arial', sans-serif; 
            font-size: 10pt; 
            line-height: 1.5;
        }
        .header { 
            display: table;
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10pt;
            margin-bottom: 15pt;
        }
        .header-left, .header-right {
            display: table-cell;
            width: 38%;
            vertical-align: middle;
            font-weight: bold;
            font-size: 10pt;
            line-height: 1.4;
        }
        .header-center {
            display: table-cell;
            width: 24%;
            text-align: center;
            vertical-align: middle;
        }
        .header-right { 
            text-align: right;
            direction: rtl;
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
        }
        .logo {
            max-width: 70px;
            max-height: 70px;
            display: inline-block;
        }
        h1 {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            margin: 15pt 0 5pt 0;
        }
        .subtitle {
            text-align: center;
            font-size: 10pt;
            font-style: italic;
            text-decoration: underline;
            margin-bottom: 20pt;
        }
        h2 {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin: 15pt 0;
        }
        .section {
            margin-bottom: 15pt;
            text-align: justify;
        }
        .article {
            margin-bottom: 15pt;
        }
        .article h3 {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 8pt;
        }
        .article p {
            margin: 0 0 8pt 0;
            text-align: justify;
        }
        .bold { font-weight: bold; }
        .link { color: #0066cc; font-weight: bold; }
        .right { text-align: right; margin-top: 8pt; }
        .indent { margin-left: 20pt; }
        .signatures {
            border-top: 2px solid #000;
            padding-top: 20pt;
            margin-top: 20pt;
        }
        .sig-table {
            width: 100%;
        }
        .sig-cell {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding-bottom: 80pt;
        }
        .sig-title {
            font-weight: bold;
            margin-bottom: 10pt;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <div>Université Abdelmalek Essaâdi</div>
            <div>Ecole Nationale des Sciences</div>
            <div>Appliquées</div>
            <div style="text-decoration: underline;">Tétouan</div>
        </div>
        <div class="header-center">
            <img src="https://www.9rayti.com/img/etablissement/universite-abdelmalek-essaadi-tetouan_5e4c0f3a2b155.jpg" alt="Logo" class="logo" />
        </div>
        <div class="header-right">
            <div>جامعة عبد المالك السعدي</div>
            <div>المدرسة الوطنية للعلوم التطبيقية</div>
            <div style="text-decoration: underline;">تطوان</div>
        </div>
    </div>

    <!-- Title -->
    <h1>CONVENTION DE STAGE</h1>
    <p class="subtitle">(2 exemplaires imprimés en recto-verso)</p>

    <!-- ENTRE -->
    <h2>ENTRE</h2>
    <div class="section">
        <p>L'Ecole Nationale des Sciences Appliquées, Université Abdelmalek Essaâdi - Tétouan</p>
        <p>B.P. 2222, Mhannech II, Tétouan, Maroc</p>
        <p>Tél. +212 5 39 68 80 27 ; Fax. +212 39 99 46 24. Web: <span class="link">https://ensa-tetouan.ac.ma</span></p>
        <p>Représenté par le Professeur <span class="bold">Kamal REKLAOUI</span> en qualité de Directeur.</p>
        <p class="right">Ci-après, dénommé <span class="bold">l'Etablissement</span></p>
    </div>

    <!-- ET -->
    <h2>ET</h2>
    <div class="section">
        <p>La Société : <span class="bold">{{ $convention->entreprise }}</span></p>
        <p>Adresse : <span class="bold">{{ $convention->adresse_entreprise }}</span></p>
        <p>Tél : <span class="bold">{{ $convention->telephone_encadrant ?? 'N/A' }}</span> Email: <span class="bold">{{ $convention->email_encadrant ?? 'N/A' }}</span></p>
        <p>Représentée par Monsieur <span class="bold">{{ $convention->encadrant_entreprise }}</span> en qualité <span class="bold">{{ $convention->fonction_encadrant ?? 'GERANT' }}</span></p>
        <p class="right">Ci-après dénommée <span class="bold">L'ENTREPRISE</span></p>
    </div>

    <!-- Article 1 -->
    <div class="article">
        <h3>Article 1 : Engagement</h3>
        <p><span class="bold">L'ENTREPRISE</span> accepte de recevoir à titre de stagiaire <span class="bold">{{ $etudiant->prenom }} {{ $etudiant->nom }}</span> étudiant de la filière du Cycle Ingénieur <span class="bold">« {{ $inscription->filiere->nom_filiere ?? 'Génie Informatique' }} {{ $inscription->niveau->libelle ?? '1ère année' }} »</span> de l'ENSA de Tétouan, Université Abdelmalek Essaâdi (Tétouan), pour une période allant du <span class="bold">{{ $convention->date_debut->format('Y-m-d') }}</span> au <span class="bold">{{ $convention->date_fin->format('Y-m-d') }}</span></p>
        <p class="bold">En aucun cas, cette convention ne pourra autoriser les étudiants à s'absenter durant la période des contrôles ou des enseignements.</p>
    </div>

    <!-- Article 2 -->
    <div class="article">
        <h3>Article 2 : Objet</h3>
        <p>Le stage aura pour objet essentiel d'assurer l'application pratique de l'enseignement donné par <span class="bold">l'Etablissement</span>, et ce, en organisant des visites sur les installations et en réalisant des études proposées par <span class="bold">L'ENTREPRISE</span>.</p>
    </div>

    <!-- Article 3 -->
    <div class="article">
        <h3>Article 3 : Encadrement et suivi</h3>
        <p>Pour accompagner le Stagiaire durant son stage, et ainsi instaurer une véritable collaboration L'ENTREPRISE/Stagiaire/Etablissement, L'ENTREPRISE désigne Mme/Mr <span class="bold">{{ $convention->encadrant_entreprise }}</span> encadrant(e) et parrain(e), pour superviser et assurer la qualité du travail fourni par le Stagiaire.</p>
        <p>L'Etablissement désigne <span class="bold">{{ $convention->encadrantPedagogique->nom ?? 'N/A' }} {{ $convention->encadrantPedagogique->prenom ?? '' }}</span> en tant que tuteur qui procurera une assistance pédagogique</p>
    </div>

    <!-- Article 4 -->
    <div class="article">
        <h3>Article 4 : Programme:</h3>
        <p>Le thème du stage est: <span class="bold">« {{ $convention->sujet }} »</span></p>
        <p>Ce programme a été défini conjointement par <span class="bold">l'Etablissement</span>, <span class="bold">L'ENTREPRISE</span> et le <span class="bold">Stagiaire</span>.</p>
        <p class="indent">Le contenu de ce programme doit permettre au Stagiaire une réflexion en relation avec les enseignements ou le projet de fin d'études qui s'inscrit dans le programme de formation de <span class="bold">l'Etablissement</span>.</p>
    </div>

    <!-- Article 5 -->
    <div class="article">
        <h3>Article 5 : Indemnité de stage</h3>
        <p>Au cours du stage, l'étudiant ne pourra prétendre à aucun salaire de la part de <span class="bold">L'ENTREPRISE</span>.</p>
        <p>Cependant, si <span class="bold">l'ENTREPRISE</span> et l'étudiant le conviennent, ce dernier pourra recevoir une indemnité forfaitaire de la part de l'ENTREPRISE des frais occasionnés par la mission confiée à l'étudiant.</p>
    </div>

    <!-- Article 6 -->
    <div class="article">
        <h3>Article 6 : Règlement</h3>
        <p>Pendant la durée du stage, le Stagiaire reste placé sous la responsabilité de <span class="bold">l'Etablissement</span>.</p>
        <p class="bold">Cependant, l'étudiant est tenu d'informer l'école dans un délai de 24h sur toute modification portant sur la convention déjà signée, sinon il en assumera toute sa responsabilité sur son non-respect de la convention signée par l'école.</p>
        <p>Toutefois, le Stagiaire est soumis à la discipline et au règlement intérieur de <span class="bold">L'ENTREPRISE</span>.</p>
        <p>En cas de manquement, <span class="bold">L'ENTREPRISE</span> se réserve le droit de mettre fin au stage après en avoir convenu avec le Directeur de l'Etablissement.</p>
    </div>

    <!-- Article 7 -->
    <div class="article">
        <h3>Article 7 : Confidentialité</h3>
        <p>Le Stagiaire et l'ensemble des acteurs liés à son travail (l'administration de <span class="bold">l'Etablissement</span>, le parrain pédagogique ...) sont tenus au secret professionnel. Ils s'engagent à ne pas diffuser les informations recueillies à des fins de publications, conférences, communications, sans raccord préalable de <span class="bold">L'ENTREPRISE</span>. Cette obligation demeure valable après l'expiration du stage</p>
    </div>

    <!-- Article 8 -->
    <div class="article">
        <h3>Article 8 : Assurance accident de travail</h3>
        <p><span class="bold">Le stagiaire</span> devra obligatoirement souscrire une assurance couvrant la Responsabilité Civile et Accident de Travail, durant les stages et trajets effectués.</p>
        <p>En cas d'accident de travail survenant durant la période du stage, <span class="bold">L'ENTREPRISE</span> s'engage à faire parvenir immédiatement à l'Etablissement toutes les informations indispensables à la déclaration dudit accident.</p>
    </div>

    <!-- Article 9 -->
    <div class="article">
        <h3>Article 9: Evaluation de L'ENTREPRISE</h3>
        <p>Le stage accompli, le parrain établira un rapport d'appréciations générales sur le travail effectué et le comportement du Stagiaire durant son séjour chez <span class="bold">L'ENTREPRISE</span>.</p>
        <p><span class="bold">L'ENTREPRISE</span> remettra au Stagiaire une attestation indiquant la nature et la durée des travaux effectués.</p>
    </div>

    <!-- Article 10 -->
    <div class="article">
        <h3>Article 10 : Rapport de stage</h3>
        <p>A l'issue de chaque stage, le Stagiaire rédigera un rapport de stage faisant état de ses travaux et de son vécu au sein de <span class="bold">L'ENTREPRISE</span>. Ce rapport sera communiqué à <span class="bold">L'ENTREPRISE</span> et restera strictement confidentiel.</p>
    </div>

    <!-- Date -->
    <p style="text-align: center; margin: 20pt 0 30pt 40pt;">
        Fait à Tétouan en deux exemplaires, le <span class="bold">{{ now()->format('d-M-Y H:i:s') }}</span>
    </p>

    <!-- Signatures -->
    <div class="signatures">
        <table class="sig-table">
            <tr>
                <td class="sig-cell">
                    <div class="sig-title">Nom et signature du Stagiaire</div>
                    <div style="margin-top: 60pt; font-weight: bold;">{{ $etudiant->prenom }} {{ $etudiant->nom }}</div>
                </td>
                <td class="sig-cell">
                    <div class="sig-title">Le Coordonnateur de la filière</div>
                </td>
            </tr>
            <tr>
                <td class="sig-cell">
                    <div class="sig-title">Signature et cachet de L'Etablissement</div>
                </td>
                <td class="sig-cell">
                    <div class="sig-title">Signature et cachet de L'ENTREPRISE</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
