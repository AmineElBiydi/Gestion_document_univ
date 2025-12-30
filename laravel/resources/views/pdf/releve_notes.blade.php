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
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
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
    <div class="header-box">
        <div class="header-row" style="font-weight: bold; font-size: 11px;">
            <span>Université Abdelmalek Essaâdi</span>
            <span style="direction: rtl;">جامعة عبد المالك السعدي</span>
        </div>
        <div class="header-row" style="font-size: 11px;">
            <span>Année universitaire {{ $decision->inscription->anneeUniversitaire->libelle ?? 'N/A' }}</span>
            <span style="direction: rtl;">السنة الجامعية</span>
        </div>
    </div>

    <div class="school-name" style="display: flex; justify-content: space-between; font-weight: bold;">
        <span>École Nationale des Sciences Appliquées Tétouan</span>
        <span style="direction: rtl;">المدرسة الوطنية للعلوم التطبيقية بتطوان</span>
    </div>

    <!-- Title -->
    <div class="title">
        RELEVÉ DE NOTES ET RÉSULTATS
    </div>

    <div class="page-info">Page : 1 / 1</div>

    <!-- Session -->
    <div class="session-box">
        Session {{ $decision->type_session === 'normale' ? '1' : '2' }}
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
                {{ $decision->inscription->niveau->libelle ?? 'N/A' }} - {{ $decision->inscription->filiere->nom_filiere ?? 'N/A' }}
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
                        {{ $decision->inscription->niveau->code_niveau ?? 'S' }} 
                        {{ substr($decision->inscription->anneeUniversitaire->libelle ?? '', 0, 4) }}/{{ substr($decision->inscription->anneeUniversitaire->libelle ?? '', 5, 2) }}
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
    <div class="result-box">
        <span>Résultat d'admission :</span>
        <span>Moyenne: {{ number_format($decision->moyenne_annuelle, 3) }} / 20</span>
        <span>{{ $decision->decision }}</span>
        <span>Mention: {{ $decision->mention ?? 'Passable' }}</span>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="note-info">
            <p style="font-weight: bold;">Moyenne >= 10 => Validé</p>
            <p>Note < 5 => Note éliminatoire</p>
        </div>
        
        <div class="signature-right">
            <div class="stamp-placeholder">
                [Cachet et Signature]
            </div>
            <p style="margin-bottom: 5px;">Fait à Tétouan, le {{ now()->locale('fr')->isoFormat('D MMMM YYYY') }}</p>
            <p style="font-weight: bold;">Le Directeur</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Avis important : Il ne peut être délivré qu'un seul exemplaire du présent relevé de notes. Aucun duplicata ne sera fourni.</p>
        <p>ENSA Tétouan - Avenue de la Palestine Mhanech I, Tétouan - Maroc</p>
    </div>
</body>
</html>
