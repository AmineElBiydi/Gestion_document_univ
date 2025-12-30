<?php

use App\Models\Demande;
use App\Services\PDFService;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$demande = Demande::where('type_document', 'releve_notes')
    ->whereHas('releveNotes', function($query) {
        $query->whereNotNull('decision_annee_id');
    })
    ->first();

if (!$demande) {
    $demande = Demande::where('type_document', 'releve_notes')->first();
}

echo "Test de génération Year Fix pour la demande: " . $demande->num_demande . "\n";

try {
    $pdfService = app(PDFService::class);
    $path = $pdfService->genererReleveNotes($demande);
    echo "PDF généré avec succès: " . $path . "\n";
} catch (\Exception $e) {
    echo "Erreur lors de la génération: " . $e->getMessage() . "\n";
    exit(1);
}
