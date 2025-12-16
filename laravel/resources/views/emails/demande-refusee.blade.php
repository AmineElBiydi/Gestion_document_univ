<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande Non Validée</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="background: #ef4444; color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
            <h1 style="margin: 0; font-size: 24px;">Campus Admin Connect</h1>
            <p style="margin: 10px 0 0 0; font-size: 14px;">Service de Gestion Administrative</p>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px;">
            <h2 style="color: #ef4444; font-size: 20px; margin: 0 0 20px 0;">Information concernant votre demande</h2>
            
            <p>Bonjour <strong>{{ $etudiant->prenom }} {{ $etudiant->nom }}</strong>,</p>
            
            <p>Nous vous informons que votre demande de <strong>{{ $typeDocument }}</strong> n'a pas pu être validée.</p>
            
            <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 20px; margin: 20px 0;">
                <h3 style="margin: 0 0 10px 0; color: #991b1b;">Informations de votre demande</h3>
                <p><strong>Numéro:</strong> {{ $demande->num_demande }}</p>
                <p><strong>Type:</strong> {{ $typeDocument }}</p>
                <p><strong>Date:</strong> {{ $demande->date_demande->format('d/m/Y') }}</p>
                <p><strong>Statut:</strong> <span style="background: #ef4444; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">Non validée</span></p>
            </div>
            
            <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 6px; padding: 20px; margin: 20px 0;">
                <h3 style="margin: 0 0 10px 0; color: #dc2626;">Raison du refus</h3>
                <p style="margin: 0; color: #7f1d1d;">{{ $raisonRefus }}</p>
            </div>
            
            <p>Vous pouvez soumettre une nouvelle demande en corrigeant les points mentionnés ci-dessus.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}/nouvelle-demande" style="background: #ef4444; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">Nouvelle demande</a>
            </div>
            
            <p>Cordialement,<br>L'équipe administrative<br>Université</p>
        </div>
        
        <!-- Footer -->
        <div style="background: #f9fafb; padding: 20px; text-align: center; border-radius: 0 0 8px 8px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280;">
            <p>Cet email a été envoyé automatiquement. Merci de ne pas répondre directement à cet email.</p>
            <p>&copy; {{ date('Y') }} Université - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>
