<?php

namespace App\Services;

use App\Models\Demande;
use Barryvdh\DomPDF\Facade\Pdf;

class ConventionStagePDF
{
    public function generate(Demande $demande)
    {
        $convention = $demande->conventionStage;
        $etudiant = $demande->etudiant;
        $inscription = $demande->inscription;
        
        $arabic = new \ArPHP\I18N\Arabic();
        
        $data = [
            'etudiant' => $etudiant,
            'convention' => $convention,
            'inscription' => $inscription,
            'demande' => $demande,
            'univ_ar' => $arabic->utf8Glyphs("جامعة عبد المالك السعدي"),
            'ensa_ar' => $arabic->utf8Glyphs("المدرسة الوطنية للعلوم التطبيقية"),
            'tetouan_ar' => $arabic->utf8Glyphs("تطوان"),
        ];
        
        $pdf = Pdf::loadView('pdf.convention-stage', $data);
        
        // Save to storage
        $filename = 'convention_' . $demande->num_demande . '.pdf';
        $path = storage_path('app/temp/' . $filename);
        
        // Create temp directory if it doesn't exist
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        $pdf->save($path);
        
        return $path;
    }
}
