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
            background-color: #16a34a;
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
            border-left: 4px solid #16a34a;
        }
        .success-box {
            background-color: #dcfce7;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            text-align: center;
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
            color: #16a34a;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✓ Demande Validée</h1>
    </div>
    
    <div class="content">
        <p>Bonjour <strong>{{ $etudiant->prenom }} {{ $etudiant->nom }}</strong>,</p>
        
        <div class="success-box">
            <p style="margin: 0; font-size: 18px; color: #16a34a;">
                <strong>Bonne nouvelle ! Votre demande a été validée.</strong>
            </p>
        </div>
        
        <p>Nous avons le plaisir de vous informer que votre demande de <strong>{{ $typeDocument }}</strong> a été validée avec succès.</p>
        
        <div class="info-box">
            <p><span class="label">Numéro de demande :</span> {{ $demande->num_demande }}</p>
            <p><span class="label">Type de document :</span> {{ $typeDocument }}</p>
            <p><span class="label">Date de validation :</span> {{ $demande->date_traitement ? $demande->date_traitement->format('d/m/Y à H:i') : now()->format('d/m/Y à H:i') }}</p>
        </div>
        
        <p>Votre document est joint à cet email au format PDF. Vous pouvez le télécharger et l'utiliser selon vos besoins.</p>
        
        <p>Si vous avez besoin d'informations complémentaires ou si vous rencontrez un problème, n'hésitez pas à contacter le service de scolarité.</p>
        
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
