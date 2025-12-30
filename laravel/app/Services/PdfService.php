<?php

namespace App\Services;

use App\Models\Demande;
use App\Models\Etudiant;
use Illuminate\Support\Facades\Storage;

class PDFService
{
    /**
     * Generate PDF for a demande
     * 
     * @param Demande $demande
     * @return string Path to generated PDF
     */
    public function generatePDF(Demande $demande)
    {
        // Load all necessary relationships
        $demande->load([
            'etudiant',
            'inscription.filiere',
            'inscription.niveau',
            'inscription.anneeUniversitaire',
            'attestationScolaire',
            'attestationReussite.decisionAnnee',
            'releveNotes.decisionAnnee',
            'conventionStage'
        ]);
        
        $etudiant = $demande->etudiant;
        
        // Generate PDF based on document type
        switch ($demande->type_document) {
            case 'attestation_scolaire':
                return $this->generateAttestationScolaire($demande, $etudiant);
            case 'attestation_reussite':
                return $this->generateAttestationReussite($demande, $etudiant);
            case 'releve_notes':
                return $this->generateReleveNotes($demande, $etudiant);
            case 'convention_stage':
                return $this->generateConventionStage($demande, $etudiant);
            default:
                throw new \Exception('Type de document non supporté');
        }
    }

    /**
     * Generate Attestation de Scolarité PDF
     */
    private function generateAttestationScolaire(Demande $demande, Etudiant $etudiant)
    {
        // Get inscription details
        $inscription = $demande->inscription;
        
        // Prepare data for the template
        $data = [
            'nom' => strtoupper($etudiant->nom),
            'prenom' => ucfirst(strtolower($etudiant->prenom)),
            'cin' => $etudiant->cin,
            'apogee' => $etudiant->apogee,
            'date_naissance' => $etudiant->date_naissance ? $etudiant->date_naissance->format('d/m/Y') : '',
            'lieu_naissance' => $etudiant->lieu_naissance ?? '',
            'annee_universitaire' => $inscription && $inscription->anneeUniversitaire 
                ? $inscription->anneeUniversitaire->libelle 
                : now()->format('Y') . '/' . (now()->year + 1),
            'diplome' => $inscription && $inscription->filiere 
                ? $inscription->filiere->diplome ?? 'Années Préparatoires au Cycle Ingénieur'
                : 'Années Préparatoires au Cycle Ingénieur',
            'filiere' => $inscription && $inscription->filiere 
                ? $inscription->filiere->nom_filiere 
                : 'Années Préparatoires',
            'annee' => $inscription && $inscription->niveau 
                ? $inscription->niveau->libelle 
                : '',
            'date_emission' => now()->format('d/m/Y'),
            'num_demande' => $demande->num_demande,
            'num_etudiant' => $etudiant->apogee,
            'adresse' => "M'Hannech II",
            'bp' => 'B.P. 2222 Tétouan',
            'tel' => '0539968802',
            'fax' => '0539984624',
        ];

        // Generate HTML content
        $html = $this->getAttestationScolaireTemplate($data);
        
        // Convert HTML to PDF
        $filename = 'attestation_scolaire_' . $demande->num_demande . '.pdf';
        $path = storage_path('app/public/documents/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        $this->generatePDFFromHTML($html, $path);
        
        return $path;
    }

    /**
     * Generate Attestation de Réussite PDF
     */
    private function generateAttestationReussite(Demande $demande, Etudiant $etudiant)
    {
        $attestation = $demande->attestationReussite;
        $decision = $attestation ? $attestation->decisionAnnee : null;
        $inscription = $decision ? $decision->inscription : null;
        
        $data = [
            'studentName' => $etudiant->nom . ' ' . $etudiant->prenom,
            'cinNumber' => $etudiant->cin,
            'apogeeNumber' => $etudiant->apogee,
            'academicYear' => $inscription ? $inscription->anneeUniversitaire->libelle : 'N/A',
            'filiere' => $inscription && $inscription->filiere ? $inscription->filiere->nom_filiere : 'N/A',
            'decision' => $decision ? $decision->decision : 'N/A',
            'mention' => $decision ? $decision->mention : 'N/A',
            'moyenne' => $decision ? $decision->moyenne_annuelle : 'N/A',
            'dateIssued' => now()->format('d/m/Y'),
            'requestNumber' => $demande->num_demande,
        ];

        $html = $this->getAttestationReussiteTemplate($data);
        
        $filename = 'attestation_reussite_' . $demande->num_demande . '.pdf';
        $path = storage_path('app/public/documents/' . $filename);
        
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        $this->generatePDFFromHTML($html, $path);
        
        return $path;
    }

    /**
     * Generate Relevé de Notes PDF
     */
    private function generateReleveNotes(Demande $demande, Etudiant $etudiant)
    {
        $filename = 'releve_notes_' . $demande->num_demande . '.pdf';
        $path = storage_path('app/public/documents/' . $filename);
        
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        $html = '<h1>Relevé de Notes</h1><p>Document en cours de développement</p>';
        $this->generatePDFFromHTML($html, $path);
        
        return $path;
    }

    /**
     * Generate Convention de Stage PDF
     */
    private function generateConventionStage(Demande $demande, Etudiant $etudiant)
    {
        $filename = 'convention_stage_' . $demande->num_demande . '.pdf';
        $path = storage_path('app/public/documents/' . $filename);
        
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        $html = '<h1>Convention de Stage</h1><p>Document en cours de développement</p>';
        $this->generatePDFFromHTML($html, $path);
        
        return $path;
    }

    private function getAttestationScolaireTemplate(array $data)
    {
        $logoPath = storage_path('app/public/logos/ensa.png');
        $signaturePath = storage_path('app/public/tampo/image.png');
        
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        $signatureBase64 = '';
        if (file_exists($signaturePath)) {
            $signatureBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($signaturePath));
        }

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 1cm 1.5cm 1cm 1.5cm;
        }
        body {
            font-family: 'DejaVu Sans', 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .header-left {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            font-size: 8pt;
            line-height: 1.2;
        }
        .header-center {
            display: table-cell;
            width: 30%;
            text-align: center;
            vertical-align: top;
        }
        .header-right {
            display: table-cell;
            width: 35%;
            text-align: right;
            vertical-align: top;
            font-size: 9pt;
            line-height: 1.4;
            direction: rtl;
        }
        .logo-img {
            width: 70px;
            height: auto;
        }
        .title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 20px 0 20px 0;
            text-decoration: underline;
            letter-spacing: 1px;
        }
        .content {
            margin: 20px 0;
            text-align: justify;
        }
        .content p {
            margin: 10px 0;
        }
        .info-line {
            margin: 6px 0;
            line-height: 1.6;
        }
        .label {
            display: inline-block;
            width: 180px;
            font-weight: normal;
        }
        .underline {
            text-decoration: underline;
        }
        .signature-section {
            margin-top: 40px;
            text-align: right;
        }
        .signature-text {
            margin-bottom: 5px;
        }
        .signature-img {
            width: 180px;
            height: auto;
            margin: 5px 0 5px auto;
        }
        .footer-section {
            margin-top: 30px;
            font-size: 9pt;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .footer-info {
            display: table;
            width: 100%;
        }
        .footer-left {
            display: table-cell;
            width: 50%;
            font-size: 8pt;
        }
        .footer-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            font-size: 8pt;
            line-height: 1.4;
            direction: rtl;
        }
        .footer-note {
            margin-top: 15px;
            text-align: center;
            font-size: 8pt;
            font-style: italic;
        }
        .student-number {
            text-align: right;
            font-size: 9pt;
            margin-top: 10px;
        }
        .arabic-text {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            unicode-bidi: embed;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="header-left">
            <strong>ROYAUME DU MAROC</strong><br>
            Université Abdelmalek Essaâdi<br>
            Ecole Nationale des Sciences<br>
            Appliquées de<br>
            Tétouan<br>
            <u>Service des Affaires Estudiantines</u>
        </div>
        <div class="header-center">
            <img src="{$logoBase64}" class="logo-img" alt="LOGO ENTSA">
        </div>
        <div class="header-right arabic-text">
            <strong>المملكة المغربية</strong><br>
            جامعة عبد المالك السعدي<br>
            المدرسة الوطنية للعلوم التطبيقية<br>
            بتطوان<br>
            <u>مصلحة الشؤون الطلابية</u>
        </div>
    </div>

    <div class="title">ATTESTATION DE SCOLARITE</div>

    <div class="content">
        <p>Le Directeur de l'Ecole Nationale des Sciences Appliquées de Tétouan atteste que l'étudiant :</p>
        
        <div style="margin: 20px 0;">
            <div class="info-line">
                <span class="label">Monsieur</span> <strong class="underline">{$data['nom']} {$data['prenom']}</strong>
            </div>
            
            <div class="info-line">
                <span class="label">Numéro de la CIN :</span> <span class="underline">{$data['cin']}</span>
            </div>
            
            <div class="info-line">
                <span class="label">Code national de l'étudiant :</span> <span class="underline">{$data['apogee']}</span>
            </div>
            
            <div class="info-line">
                né le <span class="underline">{$data['date_naissance']}</span> à <span class="underline">{$data['lieu_naissance']}</span>
            </div>
        </div>

        <p>Poursuit ses études à l'Ecole Nationale des Sciences Appliquées Tétouan pour l'année universitaire <strong>{$data['annee_universitaire']}</strong></p>

        <div style="margin: 20px 0;">
            <div class="info-line">
                <span class="label"><u>Diplôme</u></span> {$data['diplome']}
            </div>
            
            <div class="info-line">
                <span class="label"><u>Filière</u></span> {$data['filiere']}
            </div>
            
            <div class="info-line">
                <span class="label"><u>Année</u></span> {$data['annee']}
            </div>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-text">
            Fait à TETOUAN, le {$data['date_emission']}
        </div>
        <div class="signature-text">
            Le Directeur
        </div>
        <img src="{$signatureBase64}" class="signature-img" alt="Signature">
    </div>

    <div class="footer-section">
        <div class="footer-info">
            <div class="footer-left">
                <strong>Adresse</strong> {$data['adresse']}<br>
                {$data['bp']}<br>
                Tél: {$data['tel']} FAX : {$data['fax']}
            </div>
            <div class="footer-right arabic-text">
                <strong>العنوان</strong> {$data['adresse']}<br>
                ص ب {$data['bp']}<br>
                {$data['tel']} :الهاتف {$data['fax']} :الفاكس
            </div>
        </div>
        
        <div class="footer-note">
            Le présent document n'est délivré qu'en un seul exemplaire.<br>
            Il appartient à l'étudiant d'en faire des photocopies certifiées conformes.
        </div>
        <div class="student-number">
            <strong>N°étudiant :</strong> {$data['num_etudiant']}
        </div>
    </div>
</body>
</html>
HTML;
    }


    /**
     * Get HTML template for Attestation de Réussite
     */
    private function getAttestationReussiteTemplate(array $data)
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 40px 0;
            text-decoration: underline;
        }
        .content {
            margin: 30px 0;
        }
        .info {
            margin: 20px 0;
            line-height: 2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>École Nationale des Sciences Appliquées de Tétouan</h1>
    </div>

    <div class="title">ATTESTATION DE RÉUSSITE</div>

    <div class="content">
        <p>Le Directeur de l'École Nationale des Sciences Appliquées de Tétouan atteste que :</p>
        
        <div class="info">
            <strong>Nom et Prénom :</strong> {$data['studentName']}<br>
            <strong>CIN :</strong> {$data['cinNumber']}<br>
            <strong>Code Apogée :</strong> {$data['apogeeNumber']}<br>
            <strong>Filière :</strong> {$data['filiere']}<br>
            <strong>Année universitaire :</strong> {$data['academicYear']}<br>
            <strong>Décision :</strong> {$data['decision']}<br>
            <strong>Mention :</strong> {$data['mention']}<br>
            <strong>Moyenne :</strong> {$data['moyenne']}<br>
        </div>

        <p>La présente attestation est délivrée à l'intéressé(e) pour servir et valoir ce que de droit.</p>
    </div>

    <div class="footer">
        <p>Fait à Tétouan, le {$data['dateIssued']}</p>
        <p><strong>Le Directeur</strong></p>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Generate PDF from HTML using DomPDF
     */
    private function generatePDFFromHTML($html, $path)
    {
        // Check if DomPDF is available
        if (class_exists('\Dompdf\Dompdf')) {
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('isFontSubsettingEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');
            
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            file_put_contents($path, $dompdf->output());
        } else {
            // Fallback: Create a simple text file
            $textContent = strip_tags($html);
            file_put_contents($path, $textContent);
        }
    }
}