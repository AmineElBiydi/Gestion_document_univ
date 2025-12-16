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
        
        $data = [
            'etudiant' => $etudiant,
            'convention' => $convention,
            'inscription' => $inscription,
            'demande' => $demande,
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
