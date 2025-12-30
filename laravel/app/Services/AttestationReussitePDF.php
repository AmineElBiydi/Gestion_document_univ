<?php

namespace App\Services;

use App\Models\Demande;
use Barryvdh\DomPDF\Facade\Pdf;
use ArPHP\I18N\Arabic;

class AttestationReussitePDF
{
    public function generate(Demande $demande)
    {
        $etudiant = $demande->etudiant;
        $inscription = $demande->inscription;
        
        // Use latest inscription if not directly associated
        if (!$inscription) {
            $inscription = $etudiant->inscriptions()->orderBy('created_at', 'desc')->first();
        }

        $arabic = new Arabic();
        
        $data = [
            'etudiant' => $etudiant,
            'inscription' => $inscription,
            'demande' => $demande,
            'demande' => $demande,
            'univ_ar' => $arabic->utf8Glyphs("جامعة عبد المالك السعدي"),
        ];

        // Load signature
        $signaturePath = storage_path('app/public/tampo/img.png');
        if (file_exists($signaturePath)) {
            $data['signatureBase64'] = 'data:image/png;base64,' . base64_encode(file_get_contents($signaturePath));
        } else {
            $data['signatureBase64'] = null;
        }
        
        $pdf = Pdf::loadView('pdf.attestation-reussite', $data);
        
        // Save to storage
        $filename = 'attestation_reussite_' . $demande->num_demande . '.pdf';
        $path = storage_path('app/temp/' . $filename);
        
        // Create temp directory if it doesn't exist
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        $pdf->save($path);
        
        return $path;
    }
}
