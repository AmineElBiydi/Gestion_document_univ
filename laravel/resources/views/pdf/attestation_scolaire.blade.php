<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0.8cm 1.2cm 0.8cm 1.2cm;
        }
        body {
            font-family: 'DejaVu Sans', 'Times New Roman', Times, serif;
            font-size: 10.5pt;
            line-height: 1.35;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header-container {
            width: 100%;
            margin-bottom: 15px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-left {
            width: 35%;
            vertical-align: top;
            font-size: 8pt;
            line-height: 1.15;
        }
        .header-center {
            width: 30%;
            text-align: center;
            vertical-align: top;
        }
        .header-right {
            width: 35%;
            text-align: right;
            vertical-align: top;
            font-size: 9pt;
            line-height: 1.3;
        }
        .logo-img {
            width: 75px;
            height: auto;
        }
        .title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 20px 0;
            text-decoration: underline;
            letter-spacing: 1px;
        }
        .content {
            margin: 15px 0;
        }
        .content p {
            margin: 8px 0;
        }
        .info-section {
            margin: 15px 0;
        }
        .info-line {
            margin: 5px 0;
            line-height: 1.5;
        }
        .label {
            display: inline-block;
            width: 200px;
        }
        .underline {
            font-weight: bold;
        }
        .signature-section {
            margin-top: 30px;
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-text {
            margin-bottom: 3px;
        }
        .signature-img {
            width: 160px;
            height: auto;
            display: block;
            margin: 5px auto;
        }
        .footer-section {
            clear: both;
            margin-top: 30px;
            font-size: 8.5pt;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-left {
            width: 50%;
            font-size: 7.5pt;
            vertical-align: top;
        }
        .footer-right {
            width: 50%;
            text-align: right;
            font-size: 7.5pt;
            line-height: 1.3;
            vertical-align: top;
        }
        .footer-note {
            margin-top: 15px;
            text-align: center;
            font-size: 8pt;
            font-style: italic;
        }
        .student-number {
            text-align: left;
            font-size: 8.5pt;
            margin-top: 5px;
            font-weight: bold;
        }
        .arabic-text {
            font-family: 'DejaVu Sans', sans-serif;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <strong>ROYAUME DU MAROC</strong><br>
                    Université Abdelmalek Essaâdi<br>
                    Ecole Nationale des Sciences<br>
                    Appliquées de<br>
                    Tétouan<br>
                    <span style="text-decoration: underline">Service des Affaires Estudiantines</span>
                </td>
                <td class="header-center">
                    @if(isset($logoBase64) && $logoBase64)
                        <img src="{{ $logoBase64 }}" class="logo-img" alt="LOGO ENSA">
                    @endif
                </td>
                <td class="header-right arabic-text">
                    <strong>{{ $royaume_ar }}</strong><br>
                    {{ $univ_ar }}<br>
                    {{ $ecole_ar_1 }}<br>
                    {{ $ecole_ar_2 }}<br>
                    <span style="text-decoration: underline">{{ $service_ar }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="title">ATTESTATION DE SCOLARITE</div>

    <div class="content">
        <p>Le Directeur de l'Ecole Nationale des Sciences Appliquées de Tétouan atteste que l'étudiant :</p>
        
        <div class="info-section">
            <div class="info-line">
                <span class="label">{{ ($etudiant->sexe ?? 'M') === 'F' ? 'Mademoiselle' : 'Monsieur' }}</span> 
                <span class="underline">{{ strtoupper($etudiant->nom ?? '') }} {{ strtoupper($etudiant->prenom ?? '') }}</span>
            </div>
            
            <div class="info-line">
                <span class="label">Numéro de la CIN :</span> <span class="underline">{{ $etudiant->cin ?? '' }}</span>
            </div>
            
            <div class="info-line">
                <span class="label">Code national de l'étudiant :</span> <span class="underline">{{ $etudiant->apogee ?? '' }}</span>
            </div>
            
            <div class="info-line">
                <span class="label">Né le :</span> <span class="underline">{{ $etudiant->date_naissance ? $etudiant->date_naissance->format('d/m/Y') : '' }}</span>
                à <span class="underline">{{ $etudiant->lieu_naissance ?? '' }}</span>
            </div>
        </div>

        <p>Poursuit ses études à l'Ecole Nationale des Sciences Appliquées Tétouan pour l'année universitaire <strong>{{ $inscription->anneeUniversitaire->libelle ?? now()->format('Y').'/'.(now()->year+1) }}</strong></p>

        <div class="info-section">
            <div class="info-line">
                <span class="label"><span style="text-decoration: underline">Diplôme</span></span> 
                <span>{{ $inscription->filiere->diplome ?? 'Années Préparatoires au Cycle Ingénieur' }}</span>
            </div>
            
            <div class="info-line">
                <span class="label"><span style="text-decoration: underline">Filière</span></span> 
                <span>{{ $inscription->filiere->nom_filiere ?? 'Années Préparatoires' }}</span>
            </div>
            
            <div class="info-line">
                <span class="label"><span style="text-decoration: underline">Année</span></span> 
                <span style="font-weight: bold; font-size: 1.15em;">{{ $inscription->niveau->libelle ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-text" style="font-size: 10pt;">
            Fait à TETOUAN, le {{ now()->format('d/m/Y') }}
        </div>
        <div class="signature-text" style="font-weight: bold; margin-top: 5px;">
            Le Directeur
        </div>
        @if(isset($signatureBase64) && $signatureBase64)
            <img src="{{ $signatureBase64 }}" class="signature-img" alt="Signature">
        @endif
        <div style="font-weight: bold; margin-top: 5px;">Kamal REKLAOUI</div>
    </div>

    <div class="footer-section">
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    <strong>Adresse :</strong> M'Hannech II B.P. 2222 Tétouan<br>
                    Tél: 0539968802 FAX : 0539984624
                </td>
                <td class="footer-right arabic-text">
                    {{ $adresse_ar }} : <strong>العنوان</strong><br>
                    {{ $tel_ar }}
                </td>
            </tr>
        </table>
        
        <div class="footer-note">
            Le présent document n'est délivré qu'en un seul exemplaire.<br>
            Il appartient à l'étudiant d'en faire des photocopies certifiées conformes.
        </div>
    </div>
</body>
</html>
