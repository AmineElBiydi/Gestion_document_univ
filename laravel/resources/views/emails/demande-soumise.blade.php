<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
        .info-box { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #4F46E5; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
        .button { display: inline-block; padding: 12px 24px; background: #4F46E5; color: white; text-decoration: none; border-radius: 6px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Demande Enregistrée</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $demande->etudiant->prenom }} {{ $demande->etudiant->nom }}</strong>,</p>
            
            <p>Votre demande a été enregistrée avec succès dans notre système.</p>
            
            <div class="info-box">
                <p><strong>Numéro de demande :</strong> {{ $demande->num_demande }}</p>
                <p><strong>Type de document :</strong> {{ $demande->type_document }}</p>
                <p><strong>Date de demande :</strong> {{ $demande->date_demande->format('d/m/Y à H:i') }}</p>
                <p><strong>Statut :</strong> En attente de traitement</p>
            </div>
            
            <p>Vous pouvez suivre l'état de votre demande en utilisant le numéro ci-dessus.</p>
            
            <p>Nous vous informerons par email dès que votre demande sera traitée.</p>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            <p>&copy; {{ date('Y') }} Université - Gestion des Documents</p>
        </div>
    </div>
</body>
</html>
