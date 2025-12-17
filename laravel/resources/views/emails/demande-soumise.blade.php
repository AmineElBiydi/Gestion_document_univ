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
            background-color: #1e40af;
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
            border-left: 4px solid #1e40af;
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
            color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Confirmation de votre demande</h1>
    </div>
    
    <div class="content">
        <p>Bonjour <strong>{{ $etudiant->prenom }} {{ $etudiant->nom }}</strong>,</p>
        
        <p>Nous avons bien reçu votre demande de <strong>{{ $typeDocument }}</strong>.</p>
        
        <div class="info-box">
            <p><span class="label">Numéro de demande :</span> {{ $demande->num_demande }}</p>
            <p><span class="label">Type de document :</span> {{ $typeDocument }}</p>
            <p><span class="label">Date de soumission :</span> {{ $demande->date_demande->format('d/m/Y à H:i') }}</p>
            <p><span class="label">Statut :</span> En attente de traitement</p>
        </div>
        
        <p>Votre demande sera traitée dans les plus brefs délais. Vous recevrez un email de notification dès qu'elle sera validée ou si des informations complémentaires sont nécessaires.</p>
        
        <p>Vous pouvez suivre l'état de votre demande à tout moment en utilisant votre numéro de demande sur notre plateforme.</p>
        
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
