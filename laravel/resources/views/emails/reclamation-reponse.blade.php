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
            background-color: {{ $reclamation->is_valide ? '#10b981' : '#ef4444' }};
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
            border-left: 4px solid #f59e0b;
        }
        .response-box {
            background-color: {{ $reclamation->is_valide ? '#ecfdf5' : '#fef2f2' }};
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid {{ $reclamation->is_valide ? '#10b981' : '#ef4444' }};
        }
        .status-text {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
            color: {{ $reclamation->is_valide ? '#059669' : '#dc2626' }};
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
            color: #f59e0b;
        }
        .response-label {
            font-weight: bold;
            color: {{ $reclamation->is_valide ? '#059669' : '#dc2626' }};
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Réponse à votre Réclamation</h1>
    </div>
    
    <div class="content">
        <p>Bonjour <strong>{{ $etudiant->prenom }} {{ $etudiant->nom }}</strong>,</p>
        
        <p>Votre réclamation concernant la demande <strong>{{ $reclamation->demande->num_demande ?? 'N/A' }}</strong> a été traitée par notre équipe administrative.</p>
        
        <div class="status-text">
            @if($reclamation->is_valide)
                <span style="color: #059669;">✓ Votre réclamation a été acceptée et validée.</span>
            @else
                <span style="color: #dc2626;">✗ Votre réclamation a été refusée.</span>
            @endif
        </div>

        <div class="info-box">
            <p><span class="label">Votre message :</span></p>
            <p>{{ $reclamation->description }}</p>
            <p><span class="label">Date de soumission :</span> {{ $reclamation->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        
        <div class="response-box">
            <p><span class="response-label">Réponse de l'administration ({{ $adminNom }}) :</span></p>
            <p>{{ $reponse }}</p>
        </div>
        
        <p>Si vous avez d'autres questions, n'hésitez pas à nous contacter.</p>
        
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
