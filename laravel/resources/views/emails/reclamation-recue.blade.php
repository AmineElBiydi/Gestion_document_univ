<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réclamation Reçue</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="background: #f59e0b; color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
            <h1 style="margin: 0; font-size: 24px;">Campus Admin Connect</h1>
            <p style="margin: 10px 0 0 0; font-size: 14px;">Service de Gestion Administrative</p>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px;">
            <h2 style="color: #f59e0b; font-size: 20px; margin: 0 0 20px 0;">Réclamation Reçue</h2>
            
            <p>Bonjour <strong>{{ $etudiant->prenom }} {{ $etudiant->nom }}</strong>,</p>
            
            <p>Nous vous confirmons la bonne réception de votre réclamation concernant <strong>{{ $typeReclamation }}</strong>.</p>
            
            <div style="background: #fffbeb; border-left: 4px solid #f59e0b; padding: 20px; margin: 20px 0;">
                <h3 style="margin: 0 0 10px 0; color: #92400e;">Informations de votre réclamation</h3>
                <p><strong>Numéro:</strong> #{{ $reclamation->id }}</p>
                <p><strong>Type:</strong> {{ $typeReclamation }}</p>
                <p><strong>Date de soumission:</strong> {{ $reclamation->created_at->format('d/m/Y à H:i') }}</p>
                <p><strong>Statut:</strong> <span style="background: #f59e0b; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">En traitement</span></p>
            </div>
            
            <div style="background: #fef3c7; border-radius: 6px; padding: 20px; margin: 20px 0;">
                <h3 style="margin: 0 0 10px 0; color: #78350f;">Votre message</h3>
                <p style="margin: 0; color: #92400e; font-style: italic;">"{{ $reclamation->description }}"</p>
            </div>
            
            <p>Votre réclamation est maintenant en cours de traitement par notre équipe. Nous examinerons attentivement votre demande et vous apporterons une réponse dans les plus brefs délais.</p>
            
            <p>Le délai de traitement moyen est de 2 à 5 jours ouvrables.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}/espace-etudiant/reclamations/{{ $reclamation->id }}" style="background: #f59e0b; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">Suivre ma réclamation</a>
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
