<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
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
            background-color: #dc2626;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .info-box {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #dc2626;
        }
        .warning-box {
            background-color: #fee2e2;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            font-size: 12px;
            color: #6b7280;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        .label {
            font-weight: bold;
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Demande Refusée</h1>
    </div>
    
    <div class="content">
        <p>Bonjour <strong>{{ $etudiant->prenom }} {{ $etudiant->nom }}</strong>,</p>
        
        <p>Nous regrettons de vous informer que votre demande de <strong>{{ $typeDocument }}</strong> n'a pas pu être validée.</p>
        
        <div class="info-box">
            <p><span class="label">Numéro de demande :</span> {{ $demande->num_demande }}</p>
            <p><span class="label">Type de document :</span> {{ $typeDocument }}</p>
            <p><span class="label">Date de traitement :</span> {{ $demande->date_traitement ? $demande->date_traitement->format('d/m/Y à H:i') : now()->format('d/m/Y à H:i') }}</p>
        </div>
        
        <div class="warning-box">
            <p><strong>Motif du refus :</strong></p>
            <p>{{ $raisonRefus }}</p>
        </div>
        
        <p>Si vous pensez qu'il s'agit d'une erreur ou si vous souhaitez obtenir plus d'informations, vous pouvez :</p>
        <ul>
            <li>Soumettre une nouvelle demande avec les informations correctes</li>
            <li>Déposer une réclamation via notre plateforme</li>
            <li>Contacter directement le service de scolarité</li>
        </ul>
        
        <p>Nous restons à votre disposition pour toute question.</p>
        
        <p>Cordialement,<br>
        <strong>Le Service de Scolarité</strong><br>
        École Nationale des Sciences Appliquées de Tétouan</p>
    </div>
    
    <div class="footer">
        <p>Ceci est un email automatique, merci de ne pas y répondre.</p>
        <p>Pour toute question, veuillez contacter le service de scolarité.</p>
    </div>
</body>
</html>
