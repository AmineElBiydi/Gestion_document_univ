<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { 
            margin: 20mm 25mm; 
            size: A4;
        }
        body { 
            font-family: 'DejaVu Sans', 'Arial', sans-serif; 
            font-size: 11pt; 
            line-height: 1.5;
            color: #000;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 40pt;
        }
        .univ-name {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 0.5pt;
            margin-bottom: 3pt;
        }
        .univ-name-ar {
            font-size: 12pt;
            margin-bottom: 15pt;
        }
        .title-box {
            display: inline-block;
            border: 2.5px solid #000;
            padding: 8pt 50pt;
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1pt;
            margin-top: 5pt;
        }
        .content {
            margin-top: 50pt;
            text-align: center;
            position: relative;
        }
        .directeur-text {
            font-size: 11pt;
            margin-bottom: 30pt;
            line-height: 1.4;
        }
        .student-info {
            font-size: 11pt;
            line-height: 1.8;
        }
        .student-info p {
            margin: 8pt 0;
        }
        .bold { 
            font-weight: bold; 
        }
        .diagonal-line {
            position: absolute;
            top: 280pt;
            left: -50pt;
            width: 650pt;
            height: 1.5pt;
            background-color: #000;
            transform: rotate(-35deg);
            z-index: 1;
        }
        .signature-section {
            /* Handled inline/flow */
        }
        .footer-info {
            position: absolute;
            bottom: 40pt;
            left: 0;
            right: 0;
            width: 100%;
        }
        .footer-row {
            display: flex;
            justify-content: space-between;
            padding: 0 30pt;
            font-size: 10pt;
            margin-bottom: 8pt;
        }
        .student-id {
            text-align: left;
        }
        .directeur-footer {
            text-align: right;
            font-weight: bold;
        }
        .notice {
            border-top: 1px solid #000;
            padding-top: 8pt;
            margin: 8pt 30pt 0 30pt;
            font-size: 8.5pt;
            text-align: left;
            line-height: 1.3;
        }
        .underline {
            border-bottom: 2px solid #000;
            display: inline-block;
            padding-bottom: 1pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="univ-name">UNIVERSITÉ ABDELMALEK ESSAÂDI</div>
        <div class="univ-name-ar">{{ $univ_ar }}</div>
        <div class="title-box">ATTESTATION DE RÉUSSITE</div>
    </div>

    <div class="content">
        <p class="directeur-text">
            Le Directeur de l'Ecole Nationale des Sciences Appliquées de Tétouan atteste que
        </p>
        
        <div class="student-info">
            <p class="bold">
                {{ $etudiant->sexe === 'F' ? 'Mademoiselle' : 'Monsieur' }} 
                {{ strtoupper($etudiant->nom) }} {{ strtoupper($etudiant->prenom) }}
            </p>
            <p>
                née le {{ $etudiant->date_naissance ? \Carbon\Carbon::parse($etudiant->date_naissance)->translatedFormat('d F Y') : 'N/A' }} 
                à {{ strtoupper($etudiant->lieu_naissance) }}
            </p>
            <p>a été déclarée admise au niveau</p>
            <p class="bold">
                {{ $inscription->niveau->libelle ?? 'N/A' }} du Cycle Ingénieur: {{ $inscription->filiere->nom_filiere ?? 'N/A' }}
            </p>
            <p>
                au titre de l'année universitaire 
                <span class="underline">{{ $inscription->anneeUniversitaire->libelle ?? 'N/A' }}</span>
            </p>
        </div>

        
    </div>

    <div class="footer-layout" style="margin-top: 50pt; padding-right: 40pt;">
        <div class="date-location" style="text-align: right; margin-bottom: 20pt; font-size: 10pt;">
            Fait à TETOUAN, le {{ now()->translatedFormat('d F Y') }}
        </div>

        <div class="signature-section" style="text-align: right; margin-top: 20px;">
            <div class="signature-box" style="display: inline-block; text-align: center; min-width: 200px;">
                <div class="signature-title" style="font-weight: bold; margin-bottom: 20px;">Le Directeur</div>
                <div class="stamp-area" style="height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                    @if(isset($signatureBase64))
                        <img src="{{ $signatureBase64 }}" style="max-width: 180px; max-height: 100px;" alt="Signature">
                    @endif
                </div>
                <div class="signature-name" style="font-weight: bold;">Kamal REKLAOUI</div>
            </div>
        </div>
    </div>

    <div class="footer-info">
        <div class="footer-row">
            <div class="student-id">N° étudiant : &nbsp;&nbsp;&nbsp; {{ $etudiant->apogee }}</div>
            <div class="directeur-footer">Le Directeur</div>
        </div>
        <div class="notice">
            Avis important: Il ne peut être délivré qu'un seul exemplaire de cette attestation. Aucun duplicata ne sera fourni.
        </div>
    </div>
</body>
</html>