<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relevé de Notes - {{ $etudiant->nom }} {{ $etudiant->prenom }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 10pt; 
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }
        .arabic-text {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
        }
        .header-box {
            border: 2px solid #000;
            padding: 10px;
            margin-bottom: 15px;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .school-name {
            font-size: 10px;
            margin-bottom: 10px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            border: 2px solid #000;
            padding: 8px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .page-info {
            text-align: right;
            font-size: 10px;
            margin-bottom: 10px;
        }

        .session-box {
            text-align: center;
            border: 1px solid #000;
            padding: 8px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .student-info {
            margin-bottom: 20px;
            font-size: 11px;
            border: 1px solid #000;
            padding: 15px;
        }

        .student-name {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .info-row {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
        }

        .inscription-info {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #d3d3d3;
            font-weight: bold;
            text-align: center;
        }

        td.module-name {
            text-align: left;
        }

        td.centered {
            text-align: center;
        }

        .result-box {
            margin-top: 20px;
            border: 2px solid #000;
            padding: 12px;
            background-color: #f5f5f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .note-info {
            font-size: 10px;
        }

        .signature-right {
            text-align: center;
            font-size: 10px;
        }

        .stamp-placeholder {
            width: 120px;
            height: 80px;
            border: 1px dashed #ccc;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: #999;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            border-top: 1px solid #000;
            padding-top: 10px;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <table style="width: 100%; border: 2px solid #000; border-collapse: collapse; margin-bottom: 10px;">
        <tr>
            <td style="border: none; padding: 5px; font-weight: bold; font-size: 11px; width: 50%;">Université Abdelmalek Essaâdi</td>
            <td style="border: none; padding: 5px; text-align: right; width: 50%;" class="arabic-text">{{ $univ_ar ?? 'جامعة عبد المالك السعدي' }}</td>
        </tr>
        <tr>
            <td style="border: none; padding: 5px; font-size: 11px; width: 50%;">
                Année universitaire {{ str_replace('-', '/', $decision->inscription->anneeUniversitaire->libelle ?? $inscription->anneeUniversitaire->libelle ?? 'N/A') }}
            </td>
            <td style="border: none; padding: 5px; text-align: right; width: 50%;" class="arabic-text">{{ $annee_univ_ar ?? 'السنة الجامعية' }}</td>
        </tr>
    </table>

    <table style="width: 100%; margin-bottom: 15px;">
        <tr>
            <td style="border: none; font-weight: bold; font-size: 11px; width: 60%;">École Nationale des Sciences Appliquées - Tétouan</td>
            <td style="border: none; text-align: right; width: 40%; font-weight: bold;" class="arabic-text">{{ $ecole_ar ?? 'المدرسة الوطنية للعلوم التطبيقية - تطوان' }}</td>
        </tr>
    </table>

    <!-- Title -->
    <div class="title">
        RELEVÉ DE NOTES ET RÉSULTATS
    </div>

    <div class="page-info">Page : 1 / 1</div>

    <!-- Session -->
    <div class="session-box">
        Session {{ ($decision && $decision->type_session === 'rattrapage') ? '2' : '1' }}
    </div>

    <!-- Student Info -->
    <div class="student-info">
        <div class="student-name">{{ $etudiant->nom }} {{ $etudiant->prenom }}</div>
        
        <div class="info-row">
            <span class="info-label">N° Étudiant:</span> {{ $etudiant->apogee }}
            &nbsp;&nbsp;&nbsp;&nbsp;
            <span class="info-label">CNE / CIN:</span> {{ $etudiant->cin }}
        </div>
        
        @if($etudiant->date_naissance)
        <div class="info-row">
            <span class="info-label">Né le :</span> {{ \Carbon\Carbon::parse($etudiant->date_naissance)->format('d F Y') }}
            @if($etudiant->lieu_naissance)
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="info-label">à :</span> {{ $etudiant->lieu_naissance }}
            @endif
        </div>
        @endif
        
        <div class="inscription-info">
            <span class="info-label">Inscrit en :</span> 
            <span style="font-weight: bold;">
                {{ $decision->inscription->niveau->libelle ?? $inscription->niveau->libelle ?? 'N/A' }} - {{ $decision->inscription->filiere->nom_filiere ?? $inscription->filiere->nom_filiere ?? 'N/A' }}
            </span>
        </div>
        
        <div style="margin-top: 10px;">a obtenu les notes suivantes :</div>
    </div>

    <!-- Notes Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 50%; text-align: left;">Module</th>
                <th style="width: 20%;">Note / 20</th>
                <th style="width: 15%;">Résultat</th>
                <th style="width: 15%;">Session</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notes as $note)
                <tr>
                    <td class="module-name">{{ $note->moduleNiveau->module->nom_module ?? 'Module' }}</td>
                    <td class="centered">
                        @if($note->note === 'N/A' || is_null($note->note))
                            N/A
                        @else
                            {{ number_format($note->note, 2) }} / 20
                        @endif
                    </td>
                    <td class="centered">
                        @if($note->note === 'N/A' || is_null($note->est_valide))
                            N/A
                        @else
                            {{ $note->est_valide ? 'Validé' : 'Non Validé' }}
                        @endif
                    </td>
                    <td class="centered">
                        {{ $decision->inscription->niveau->code_niveau ?? $inscription->niveau->code_niveau ?? 'S' }} 
                        {{ str_replace('-', '/', $decision->inscription->anneeUniversitaire->libelle ?? $inscription->anneeUniversitaire->libelle ?? '') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="centered">Aucune note disponible</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Result -->
    <div class="result-box" style="display: table; width: 100%; border: 1px solid #000; padding: 5px; margin-top: 20px;">
        <div style="display: table-row;">
            <div style="display: table-cell; width: 40%; font-weight: bold;">Résultat d'admission session {{ ($decision && $decision->type_session === 'rattrapage') ? '2' : '1' }} :</div>
            <div style="display: table-cell; width: 15%; text-align: center; font-weight: bold;">{{ $decision ? number_format($decision->moyenne_annuelle, 3) : '-' }} / 20</div>
            <div style="display: table-cell; width: 15%; text-align: center; font-weight: bold;">{{ $decision ? strtoupper($decision->decision) : 'EN COURS' }}</div>
            <div style="display: table-cell; width: 15%; text-align: center; font-weight: bold;">{{ $decision->mention ?? 'Passable' }}</div>
            <div style="display: table-cell; width: 15%; text-align: right; font-weight: bold;">{{ $classement ?? '-' }}/{{ $total_etudiants ?? '-' }}</div>
        </div>
    </div>

    <!-- Signature Section -->
    <div style="margin-top: 50px; text-align: center;">
        @if(isset($signatureBase64))
            <div style="margin-left: auto; margin-right: 50px; width: 250px; text-align: center;">
                <p style="margin-bottom: 5px;">Fait à TETOUAN, le {{ now()->locale('fr')->isoFormat('D MMMM YYYY') }}</p>
                <p>Le Directeur de l'Ecole Nationale des Sciences Appliquées de Tétouan</p>
                <div style="margin-top: 10px;">
                    <img src="{{ $signatureBase64 }}" style="width: 200px; height: auto;">
                </div>
                <p style="font-weight: bold; margin-top: 5px;">Le Directeur</p>
            </div>
        @else
            <div style="margin-left: auto; margin-right: 50px; width: 250px; text-align: center;">
                <p style="margin-bottom: 5px;">Fait à TETOUAN, le {{ now()->locale('fr')->isoFormat('D MMMM YYYY') }}</p>
                <p>Le Directeur de l'Ecole Nationale des Sciences Appliquées de Tétouan</p>
                <div style="height: 80px;"></div>
                <p style="font-weight: bold;">Le Directeur</p>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div style="position: absolute; bottom: 20px; width: 100%; text-align: center; font-size: 10px; border-top: 1px solid #ccc; padding-top: 10px;">
        <p>Avis important : Il ne peut être délivré qu'un seul exemplaire du présent relevé de notes. Aucun duplicata ne sera fourni.</p>
    </div>
</body>
</html>
