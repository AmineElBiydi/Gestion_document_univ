<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11pt; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 16pt; font-weight: bold; margin: 20px 0; text-align: center; text-decoration: underline; }
        .section { margin: 15px 0; }
        .section-title { font-weight: bold; margin: 10px 0; }
        .info-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .info-table td { padding: 5px; border: 1px solid #000; }
        .signatures { margin-top: 40px; }
        .signature-box { display: inline-block; width: 45%; text-align: center; margin: 10px 2%; }
        .article { margin: 10px 0; text-align: justify; }
    </style>
</head>
<body>
    <div class="header">
        <h2>ROYAUME DU MAROC</h2>
        <h3>UNIVERSITÉ</h3>
        <p>École/Faculté</p>
    </div>
    
    <div class="title">
        CONVENTION DE STAGE
    </div>
    
    <div class="section">
        <div class="section-title">ENTRE LES SOUSSIGNÉS :</div>
        
        <p><strong>L'Université</strong>, représentée par son Directeur</p>
        
        <p>ET</p>
        
        <p><strong>{{ $convention->entreprise }}</strong><br>
        Adresse : {{ $convention->adresse_entreprise }}<br>
        Représentée par : {{ $convention->encadrant_entreprise }}<br>
        Fonction : {{ $convention->fonction_encadrant }}</p>
    </div>
    
    <div class="section">
        <div class="section-title">CONCERNANT :</div>
        <table class="info-table">
            <tr>
                <td><strong>Étudiant(e)</strong></td>
                <td>{{ $etudiant->prenom }} {{ $etudiant->nom }}</td>
            </tr>
            <tr>
                <td><strong>Apogée</strong></td>
                <td>{{ $etudiant->apogee }}</td>
            </tr>
            @if($inscription)
            <tr>
                <td><strong>Filière</strong></td>
                <td>{{ $inscription->filiere->nom_filiere ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Niveau</strong></td>
                <td>{{ $inscription->niveau->libelle ?? 'N/A' }}</td>
            </tr>
            @endif
        </table>
    </div>
    
    <div class="section">
        <div class="section-title">OBJET DU STAGE :</div>
        <p>{{ $convention->sujet }}</p>
        
        <table class="info-table">
            <tr>
                <td><strong>Date de début</strong></td>
                <td>{{ $convention->date_debut->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Date de fin</strong></td>
                <td>{{ $convention->date_fin->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Encadrant pédagogique</strong></td>
                <td>{{ $convention->encadrantPedagogique->nom ?? 'À définir' }} {{ $convention->encadrantPedagogique->prenom ?? '' }}</td>
            </tr>
            <tr>
                <td><strong>Encadrant entreprise</strong></td>
                <td>{{ $convention->encadrant_entreprise }}</td>
            </tr>
        </table>
    </div>
    
    <div class="section">
        <div class="article"><strong>Article 1 :</strong> Le présent stage s'inscrit dans le cadre de la formation académique de l'étudiant(e).</div>
        <div class="article"><strong>Article 2 :</strong> L'étudiant(e) s'engage à respecter le règlement intérieur de l'entreprise d'accueil.</div>
        <div class="article"><strong>Article 3 :</strong> L'entreprise s'engage à fournir les moyens nécessaires à la réalisation du stage.</div>
        <div class="article"><strong>Article 4 :</strong> L'étudiant(e) reste sous la responsabilité de l'université durant toute la durée du stage.</div>
    </div>
    
    <div class="signatures">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; text-align: center;">
                    <p><strong>Pour l'Université</strong></p>
                    <p>Le Directeur</p>
                    <br><br><br>
                    <p>Signature et cachet</p>
                </td>
                <td style="width: 50%; text-align: center;">
                    <p><strong>Pour l'Entreprise</strong></p>
                    <p>{{ $convention->encadrant_entreprise }}</p>
                    <br><br><br>
                    <p>Signature et cachet</p>
                </td>
            </tr>
        </table>
        
        <div style="text-align: center; margin-top: 30px;">
            <p><strong>L'Étudiant(e)</strong></p>
            <p>{{ $etudiant->prenom }} {{ $etudiant->nom }}</p>
            <br><br>
            <p>Signature</p>
        </div>
    </div>
    
    <div style="margin-top: 30px; text-align: center; font-size: 9pt;">
        <p>Fait à _____________, le {{ now()->format('d/m/Y') }}</p>
        <p>En trois exemplaires originaux</p>
    </div>
</body>
</html>
