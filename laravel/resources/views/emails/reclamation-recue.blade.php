<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f59e0b; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
        .info-box { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #f59e0b; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Réclamation Reçue</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $reclamation->etudiant->prenom }} {{ $reclamation->etudiant->nom }}</strong>,</p>
            
            <p>Votre réclamation a bien été enregistrée.</p>
            
            <div class="info-box">
                <p><strong>Numéro de demande concernée :</strong> {{ $reclamation->demande->num_demande }}</p>
                <p><strong>Type de réclamation :</strong> {{ $reclamation->type }}</p>
                <p><strong>Date :</strong> {{ $reclamation->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            
            <p>Nous traiterons votre réclamation dans les plus brefs délais.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Université - Gestion des Documents</p>
        </div>
    </div>
</body>
</html>
