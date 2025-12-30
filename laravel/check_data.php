<?php

use App\Models\Demande;
use App\Models\Etudiant;
use App\Models\Inscription;
use App\Models\DecisionAnnee;
use App\Models\Note;

// Statistiques
echo "📊 STATISTIQUES DE LA BASE:\n";
echo str_repeat("-", 50) . "\n";
echo "Étudiants: " . Etudiant::count() . "\n";
echo "Inscriptions: " . Inscription::count() . "\n";
echo "Décisions: " . DecisionAnnee::count() . "\n";
echo "Notes: " . Note::count() . "\n";
echo "Demandes: " . Demande::count() . "\n\n";

// Trouver un étudiant avec décision
$etudiantAvecDecision = Etudiant::whereHas('inscriptions.decisionsAnnee')->first();

if ($etudiantAvecDecision) {
    echo "✅ ÉTUDIANT AVEC DÉCISION TROUVÉ:\n";
    echo str_repeat("-", 50) . "\n";
    echo "Nom: {$etudiantAvecDecision->nom} {$etudiantAvecDecision->prenom}\n";
    echo "Email: {$etudiantAvecDecision->email}\n";
    echo "Apogée: {$etudiantAvecDecision->apogee}\n";
    echo "CIN: {$etudiantAvecDecision->cin}\n";
    
    $inscription = $etudiantAvecDecision->inscriptions()->with('decisionsAnnee')->first();
    if ($inscription && $inscription->decisionsAnnee->isNotEmpty()) {
        $decision = $inscription->decisionsAnnee->first();
        echo "Moyenne: {$decision->moyenne_annuelle}/20\n";
        echo "Mention: {$decision->mention}\n";
        echo "Décision: {$decision->decision}\n";
        
        $notesCount = Note::where('inscription_id', $inscription->id)->count();
        echo "Nombre de notes: {$notesCount}\n";
    }
} else {
    echo "❌ AUCUN ÉTUDIANT AVEC DÉCISION\n";
    echo "Exécutez: php artisan db:seed --class=CompleteStudentDataSeeder\n";
}

echo "\n";

// Vérifier les demandes existantes
$demandesReleveNotes = Demande::where('type_document', 'releve_notes')->get();
echo "📋 DEMANDES DE RELEVÉ DE NOTES:\n";
echo str_repeat("-", 50) . "\n";

if ($demandesReleveNotes->isEmpty()) {
    echo "Aucune demande de relevé de notes trouvée.\n";
    echo "Créez-en une depuis le frontend avec les identifiants ci-dessus.\n";
} else {
    foreach ($demandesReleveNotes as $demande) {
        echo "\nDemande: {$demande->num_demande}\n";
        echo "Status: {$demande->status}\n";
        echo "Étudiant: {$demande->etudiant->nom} {$demande->etudiant->prenom}\n";
        
        if ($demande->releveNotes && $demande->releveNotes->decisionAnnee) {
            echo "✅ Décision liée - PDF peut être généré\n";
        } else {
            echo "❌ Pas de décision - PDF ne peut pas être généré\n";
        }
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🎯 POUR TESTER L'ENVOI DE PDF:\n";
echo "1. Utilisez les identifiants de l'étudiant ci-dessus\n";
echo "2. Créez une demande de relevé de notes depuis le frontend\n";
echo "3. Validez la demande depuis l'interface admin\n";
echo "4. L'email avec PDF sera envoyé automatiquement\n";
