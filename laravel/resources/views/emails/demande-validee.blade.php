<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10b981; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
        .success-box { background: #d1fae5; padding: 15px; margin: 15px 0; border-left: 4px solid #10b981; }
        .info-box { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #4F46E5; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Demande Validée</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $demande->etudiant->prenom }} {{ $demande->etudiant->nom }}</strong>,</p>
            
            <div class="success-box">
                <p><strong>Bonne nouvelle !</strong> Votre demande a été validée par l'administration.</p>
            </div>
            
            <div class="info-box">
                <p><strong>Numéro de demande :</strong> {{ $demande->num_demande }}</p>
                <p><strong>Type de document :</strong> {{ $demande->type_document }}</p>
                <p><strong>Date de validation :</strong> {{ $demande->date_traitement->format('d/m/Y à H:i') }}</p>
            </div>
            
            @if($demande->type_document === 'convention_stage')
                <p>Vous trouverez votre convention de stage en pièce jointe de cet email.</p>
                <p><strong>Important :</strong> Veuillez imprimer, signer et faire signer la convention par votre entreprise d'accueil.</p>
            @else
                <p>Votre document est maintenant disponible. Vous pouvez le récupérer auprès du service de scolarité.</p>
            @endif
            
            <p>Merci de votre confiance.</p>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            <p>&copy; {{ date('Y') }} Université - Gestion des Documents</p>
        </div>
    </div>
</body>
</html>
