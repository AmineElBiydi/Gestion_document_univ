<?php

namespace App\Services;

use App\Models\Demande;
use App\Models\Etudiant;
use App\Models\DecisionAnnee;
use App\Models\Note;
use Illuminate\Support\Facades\Storage;
use App\Services\ConventionStagePDF;
use App\Services\AttestationReussitePDF;
use Barryvdh\DomPDF\Facade\Pdf;
use ArPHP\I18N\Arabic;

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
            'inscription.notes.moduleNiveau.module', // Load notes
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
     * Alias for generateReleveNotes for consistency with DemandeController
     */
    public function genererReleveNotes(Demande $demande)
    {
        return $this->generatePDF($demande);
    }

    private function generateAttestationScolaire(Demande $demande, Etudiant $etudiant)
    {
        // Get inscription details
        $inscription = $demande->inscription;
        
        // Fallback to latest inscription if not directly associated
        if (!$inscription) {
            $inscription = $etudiant->inscriptions()
                ->with(['filiere', 'niveau', 'anneeUniversitaire'])
                ->orderBy('created_at', 'desc')
                ->first();
        }
        
        $arabic = new Arabic();
        
        // Prepare data for the template
        $data = [
            'etudiant' => $etudiant,
            'inscription' => $inscription,
            'demande' => $demande,
            'royaume_ar' => $arabic->utf8Glyphs("المملكة المغربية"),
            'univ_ar' => $arabic->utf8Glyphs("جامعة عبد المالك السعدي"),
            'ecole_ar_1' => $arabic->utf8Glyphs("المدرسة الوطنية للعلوم التطبيقية"),
            'ecole_ar_2' => $arabic->utf8Glyphs("بتطوان"),
            'service_ar' => $arabic->utf8Glyphs("مصلحة الشؤون الطلابية"),
            'adresse_ar' => $arabic->utf8Glyphs("المحننش الثاني ص.ب 2222 تطوان"),
            'tel_ar' => $arabic->utf8Glyphs("الهاتف: 0539968802 الفاكس: 0539984624"),
        ];

        // Load logo
        $logoPath = storage_path('app/public/logos/ensa.png');
        if (file_exists($logoPath)) {
            $data['logoBase64'] = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Load signature
        $signaturePath = storage_path('app/public/tampo/img.png');
        if (file_exists($signaturePath)) {
            $data['signatureBase64'] = 'data:image/png;base64,' . base64_encode(file_get_contents($signaturePath));
        }

        // Generate PDF using Blade view
        $pdf = Pdf::loadView('pdf.attestation_scolaire', $data);
        
        $filename = 'attestation_scolaire_' . $demande->num_demande . '.pdf';
        $path = storage_path('app/public/documents/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        $pdf->save($path);
        
        return $path;
    }

    /**
     * Generate Attestation de Réussite PDF
     */
    private function generateAttestationReussite(Demande $demande, Etudiant $etudiant)
    {
        // Use the dedicated AttestationReussitePDF class with Blade view
        $generator = new AttestationReussitePDF();
        $tempPath = $generator->generate($demande);
        
        // Move from temp to public/documents for consistency
        $filename = 'attestation_reussite_' . $demande->num_demande . '.pdf';
        $finalPath = storage_path('app/public/documents/' . $filename);
        
        if (!file_exists(dirname($finalPath))) {
            mkdir(dirname($finalPath), 0755, true);
        }
        
        // Move the file
        if (file_exists($tempPath)) {
            rename($tempPath, $finalPath);
        }
        
        return $finalPath;
    }

    /**
     * Generate Relevé de Notes PDF
     */
    private function generateReleveNotes(Demande $demande, Etudiant $etudiant)
    {
        // Get details
        $inscription = $demande->inscription;
        $notes = $inscription ? $inscription->notes()->with('moduleNiveau.module')->get() : collect([]);
        $decision = $demande->releveNotes && $demande->releveNotes->decisionAnnee 
            ? $demande->releveNotes->decisionAnnee 
            : null;

        // Logic for ranking (tempo)
        $classement = '-';
        $totalEtudiants = '-';
        
        if ($decision && $inscription) {
            $query = DecisionAnnee::whereHas('inscription', function ($q) use ($inscription) {
                $q->where('filiere_id', $inscription->filiere_id)
                  ->where('niveau_id', $inscription->niveau_id)
                  ->where('annee_id', $inscription->annee_id);
            })->where('type_session', $decision->type_session);
            
            $totalEtudiants = $query->count();
            $classement = $query->where('moyenne_annuelle', '>', $decision->moyenne_annuelle)->count() + 1;
        }

        $arabic = new Arabic();

        $data = [
            'etudiant' => $etudiant,
            'inscription' => $inscription,
            'notes' => $notes,
            'decision' => $decision,
            'classement' => $classement,
            'total_etudiants' => $totalEtudiants,
            'demande' => $demande,
            'univ_ar' => $arabic->utf8Glyphs("جامعة عبد المالك السعدي"),
            'annee_univ_ar' => $arabic->utf8Glyphs("السنة الجامعية"),
            'ecole_ar' => $arabic->utf8Glyphs("المدرسة الوطنية للعلوم التطبيقية - تطوان"),
        ];

        // Load signature
        $signaturePath = storage_path('app/public/tampo/img.png');
        if (file_exists($signaturePath)) {
            $data['signatureBase64'] = 'data:image/png;base64,' . base64_encode(file_get_contents($signaturePath));
        }

        $pdf = Pdf::loadView('pdf.releve_notes', $data);

        $filename = 'releve_notes_' . $demande->num_demande . '.pdf';
        $path = storage_path('app/public/documents/' . $filename);
        
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        $pdf->save($path);
        
        return $path;
    }

    private function getReleveNotesTemplate(array $data)
    {
        $logoPath = storage_path('app/public/logos/ensa.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        $notesRows = '';
        foreach ($data['notes'] as $note) {
            $moduleName = $note->moduleNiveau && $note->moduleNiveau->module 
                ? $note->moduleNiveau->module->nom_module 
                : 'Module';
            $valeur = $note->note;
            $statut = $note->est_valide ? 'V' : 'NV';
            
            $notesRows .= "<tr>
                <td>{$moduleName}</td>
                <td style='text-align: center;'>{$valeur}/20</td>
                <td style='text-align: center;'>{$statut}</td>
            </tr>";
        }

        if (empty($notesRows)) {
            $notesRows = "<tr><td colspan='3' style='text-align: center;'>Aucune note disponible</td></tr>";
        }

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1cm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { height: 60px; margin-bottom: 10px; }
        .title { font-size: 16pt; font-weight: bold; text-decoration: underline; margin: 20px 0; text-align: center; }
        .info-block { margin-bottom: 20px; }
        .info-row { margin: 5px 0; }
        .label { font-weight: bold; width: 150px; display: inline-block; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background-color: #f0f0f0; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{$logoBase64}" class="logo"><br>
        <strong>Université Abdelmalek Essaâdi</strong><br>
        Ecole Nationale des Sciences Appliquées - Tétouan
    </div>

    <div class="title">RELEVÉ DE NOTES</div>

    <div class="info-block">
        <div class="info-row"><span class="label">Etudiant:</span> {$data['nom']} {$data['prenom']}</div>
        <div class="info-row"><span class="label">Code Apogée:</span> {$data['apogee']}</div>
        <div class="info-row"><span class="label">C.I.N:</span> {$data['cin']}</div>
        <div class="info-row"><span class="label">Filière:</span> {$data['filiere']}</div>
        <div class="info-row"><span class="label">Niveau:</span> {$data['niveau']}</div>
        <div class="info-row"><span class="label">Année Univ:</span> {$data['annee_universitaire']}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Module</th>
                <th>Note</th>
                <th>Résultat</th>
            </tr>
        </thead>
        <tbody>
            {$notesRows}
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align: right; font-weight: bold;">Moyenne Générale</td>
                <td style="text-align: center; font-weight: bold;">{$data['moyenne']}/20</td>
                <td>Run: {$data['session']} - {$data['resultat']}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Fait à Tétouan, le {$data['date_emission']}<br><br>
        <strong>Le Directeur</strong>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Generate Convention de Stage PDF
     */
    private function generateConventionStage(Demande $demande, Etudiant $etudiant)
    {
        // Use the dedicated ConventionStagePDF class with Blade view
        $generator = new ConventionStagePDF();
        $tempPath = $generator->generate($demande);
        
        // Move from temp to public/documents for consistency
        $filename = 'convention_stage_' . $demande->num_demande . '.pdf';
        $finalPath = storage_path('app/public/documents/' . $filename);
        
        if (!file_exists(dirname($finalPath))) {
            mkdir(dirname($finalPath), 0755, true);
        }
        
        // Move the file
        if (file_exists($tempPath)) {
            rename($tempPath, $finalPath);
        }
        
        return $finalPath;
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
     * Generate Historique PDF
     */
    public function generateHistoriquePDF($demandes)
    {
        $logoPath = storage_path('app/public/logos/ensa.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        $rows = '';
        foreach ($demandes as $demande) {
            $etudiant = $demande->etudiant ? $demande->etudiant->prenom . ' ' . $demande->etudiant->nom : 'Inconnu';
            $apogee = $demande->etudiant ? $demande->etudiant->apogee : '-';
            $document = $demande->getTypeDocumentLabel();
            $created = $demande->created_at ? $demande->created_at->format('d/m/Y') : '-';
            $processed = $demande->date_traitement ? \Carbon\Carbon::parse($demande->date_traitement)->format('d/m/Y') : '-';
            $status = ucfirst($demande->status);
            
            // Translate status
            if ($demande->status == 'validee') $status = 'Validée';
            if ($demande->status == 'rejetee') $status = 'Refusée';
            if ($demande->status == 'en_attente') $status = 'En attente';

            $rows .= "<tr>
                <td>{$demande->num_demande}</td>
                <td>{$etudiant}</td>
                <td>{$apogee}</td>
                <td>{$document}</td>
                <td>{$created}</td>
                <td>{$processed}</td>
                <td>{$status}</td>
            </tr>";
        }

        $date = now()->format('d/m/Y H:i');

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { height: 50px; margin-bottom: 10px; }
        .title { font-size: 14pt; font-weight: bold; margin: 15px 0; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .footer { margin-top: 20px; text-align: right; font-size: 8pt; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{$logoBase64}" class="logo"><br>
        <strong>Université Abdelmalek Essaâdi</strong><br>
        Ecole Nationale des Sciences Appliquées - Tétouan
    </div>

    <div class="title">Historique des Demandes</div>
    <div style="text-align: center; font-size: 9pt; margin-bottom: 15px;">Généré le {$date}</div>

    <table>
        <thead>
            <tr>
                <th>N° Demande</th>
                <th>Étudiant</th>
                <th>Apogée</th>
                <th>Document</th>
                <th>Créée le</th>
                <th>Traitée le</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            {$rows}
        </tbody>
    </table>

    <div class="footer">
        Document généré automatiquement le {$date}
    </div>
</body>
</html>
HTML;

        $filename = 'historique_' . now()->format('Y-m-d_His') . '.pdf';
        $path = storage_path('app/temp/' . $filename);
        
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        $this->generatePDFFromHTML($html, $path, 'landscape');
        
        return $path;
    }

    /**
     * Generate PDF from HTML using DomPDF
     */
    private function generatePDFFromHTML($html, $path, $orientation = 'portrait')
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
            $dompdf->setPaper('A4', $orientation);
            $dompdf->render();
            file_put_contents($path, $dompdf->output());
        } else {
            // Fallback: Create a simple text file
            $textContent = strip_tags($html);
            file_put_contents($path, $textContent);
        }
    }
}