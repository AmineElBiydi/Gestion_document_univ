<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de demande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }
        .footer {
            background: #f3f4f6;
            padding: 20px;
            text-align: center;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
            font-size: 12px;
            color: #6b7280;
        }
        .info-box {
            background: white;
            padding: 20px;
            border-left: 4px solid #2563eb;
            margin: 20px 0;
        }
        .status {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Campus Admin Connect</h1>
        <p>Service de gestion des demandes administratives</p>
    </div>

    <div class="content">
        <h2>Bonjour {{ $etudiant->prenom }} {{ $etudiant->nom }},</h2>
        
        <p>Nous vous confirmons la bonne réception de votre demande de {{ $typeDocument }}.</p>

        <div class="info-box">
            <h3>Informations de votre demande</h3>
            <p><strong>Numéro de demande:</strong> {{ $demande->num_demande }}</p>
            <p><strong>Type de document:</strong> {{ $typeDocument }}</p>
            <p><strong>Date de soumission:</strong> {{ $demande->date_demande->format('d/m/Y') }}</p>
            <p><strong>Statut actuel:</strong> <span class="status">En attente de traitement</span></p>
        </div>

        <p>Votre demande est maintenant en cours de traitement par notre équipe administrative. Vous recevrez une notification par email dès qu'elle sera validée ou si des informations supplémentaires sont requises.</p>

        <p>Pour suivre l'état de votre demande, vous pouvez utiliser le numéro de référence ci-dessus.</p>

        <p>Cordialement,<br>L'équipe administrative<br>Université</p>
    </div>

    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas répondre directement à cet email.</p>
        <p>&copy; {{ date('Y') }} Université - Tous droits réservés</p>
    </div>
</body>
</html>
