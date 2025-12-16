<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #ef4444; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
        .error-box { background: #fee2e2; padding: 15px; margin: 15px 0; border-left: 4px solid #ef4444; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Demande Refusée</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $demande->etudiant->prenom }} {{ $demande->etudiant->nom }}</strong>,</p>
            
            <div class="error-box">
                <p><strong>Numéro de demande :</strong> {{ $demande->num_demande }}</p>
                <p><strong>Raison du refus :</strong> {{ $demande->raison_refus }}</p>
            </div>
            
            <p>Vous pouvez soumettre une nouvelle demande après avoir corrigé les éléments mentionnés ci-dessus.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Université - Gestion des Documents</p>
        </div>
    </div>
</body>
</html>
