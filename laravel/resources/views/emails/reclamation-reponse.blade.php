<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .info-box {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #4F46E5;
        }
        .response-box {
            background-color: #ecfdf5;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #10b981;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 24px;">Réponse à votre réclamation</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $etudiant->prenom }} {{ $etudiant->nom }}</strong>,</p>
            
            <p>Votre réclamation concernant la demande <strong>{{ $demande->num_demande }}</strong> a été traitée par notre service.</p>
            
            <div class="info-box">
                <p style="margin: 5px 0;"><strong>Votre message :</strong></p>
                <p style="margin: 5px 0; font-style: italic;">"{{ $reclamation->message }}"</p>
            </div>

            <div class="response-box">
                <p style="margin: 5px 0;"><strong>Réponse de l'administration :</strong></p>
                <p style="margin: 5px 0;">{{ $reponse }}</p>
            </div>
            
            <p>Si vous avez d'autres questions, n'hésitez pas à nous contacter.</p>
        </div>
        <div class="footer">
            <p>Ceci est un email automatique, merci de ne pas y répondre.</p>
            <p>&copy; {{ date('Y') }} Université Abdelmalek Essaâdi - ENSA Tétouan</p>
        </div>
    </div>
</body>
</html>
