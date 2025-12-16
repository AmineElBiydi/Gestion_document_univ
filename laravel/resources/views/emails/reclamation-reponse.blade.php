<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse à votre Réclamation</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 20px 0;">
                <table role="presentation" style="width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 40px 30px; text-align: center; border-radius: 8px 8px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 600;">Campus Admin Connect</h1>
                            <p style="margin: 10px 0 0 0; color: #ffffff; font-size: 14px; opacity: 0.95;">Système de Gestion Administrative</p>
                        </td>
                    </tr>
                    
                    <!-- Reply Icon -->
                    <tr>
                        <td style="text-align: center; padding: 30px 30px 20px 30px;">
                            <div style="width: 80px; height: 80px; background-color: #dbeafe; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                </svg>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 0 40px 40px 40px;">
                            <h2 style="color: #3b82f6; font-size: 24px; margin: 0 0 20px 0; text-align: center;">Réponse à votre Réclamation</h2>
                            
                            <p style="color: #374151; font-size: 16px; line-height: 1.6; margin: 0 0 25px 0;">
                                Bonjour <strong>{{ $etudiant->prenom }} {{ $etudiant->nom }}</strong>,
                            </p>
                            
                            <p style="color: #374151; font-size: 16px; line-height: 1.6; margin: 0 0 25px 0;">
                                Suite à votre réclamation, nous avons examiné attentivement votre dossier et souhaitons vous apporter une réponse détaillée.
                            </p>
                            
                            <!-- Reclamation Reference Box -->
                            <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 20px; margin: 25px 0; border-radius: 4px;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px; width: 40%;">Numéro de réclamation :</td>
                                        <td style="padding: 8px 0; color: #1f2937; font-size: 14px; font-weight: 600;">#{{ $reclamation->id }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Type de réclamation :</td>
                                        <td style="padding: 8px 0; color: #1f2937; font-size: 14px; font-weight: 600;">{{ $typeReclamation }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Date de soumission :</td>
                                        <td style="padding: 8px 0; color: #1f2937; font-size: 14px; font-weight: 600;">{{ \Carbon\Carbon::parse($reclamation->created_at)->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Date de réponse :</td>
                                        <td style="padding: 8px 0; color: #1f2937; font-size: 14px; font-weight: 600;">{{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Traité par :</td>
                                        <td style="padding: 8px 0; color: #1f2937; font-size: 14px; font-weight: 600;">{{ $adminNom ?? 'Service Administratif' }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Original Reclamation Summary -->
                            <div style="background-color: #f9fafb; border-radius: 4px; padding: 20px; margin: 25px 0;">
                                <p style="margin: 0 0 10px 0; color: #374151; font-size: 14px; font-weight: 600;">
                                    📄 Votre réclamation concernait :
                                </p>
                                <p style="margin: 0; color: #6b7280; font-size: 14px; line-height: 1.6; font-style: italic;">
                                    "{{ Str::limit($reclamation->description, 200) }}"
                                </p>
                            </div>
                            
                            <!-- Admin Response -->
                            <div style="background-color: #ffffff; border: 2px solid #3b82f6; border-radius: 6px; padding: 25px; margin: 25px 0;">
                                <p style="margin: 0 0 15px 0; color: #1e40af; font-size: 16px; font-weight: 600;">
                                    💬 Notre réponse
                                </p>
                                <div style="color: #1f2937; font-size: 15px; line-height: 1.8;">
                                    {!! nl2br(e($reponseMessage)) !!}
                                </div>
                            </div>
                            
                            <!-- Next Steps / Actions Taken -->
                            @if(isset($actionsPrises) && !empty($actionsPrises))
                            <div style="background-color: #f0fdf4; border: 1px solid #10b981; border-radius: 4px; padding: 20px; margin: 25px 0;">
                                <p style="margin: 0 0 10px 0; color: #065f46; font-size: 14px; font-weight: 600;">
                                    ✅ Actions entreprises
                                </p>
                                <p style="margin: 0; color: #047857; font-size: 14px; line-height: 1.6;">
                                    {{ $actionsPrises }}
                                </p>
                            </div>
                            @endif
                            
                            <!-- Contact Information -->
                            <div style="background-color: #fffbeb; border: 1px solid #f59e0b; border-radius: 4px; padding: 20px; margin: 25px 0;">
                                <p style="margin: 0 0 10px 0; color: #92400e; font-size: 14px; font-weight: 600;">
                                    📞 Besoin de plus d'informations ?
                                </p>
                                <p style="margin: 0 0 12px 0; color: #78350f; font-size: 14px; line-height: 1.6;">
                                    Si cette réponse ne répond pas entièrement à vos préoccupations ou si vous avez besoin de clarifications supplémentaires, n'hésitez pas à :
                                </p>
                                <ul style="margin: 0; padding-left: 20px; color: #78350f; font-size: 14px; line-height: 1.7;">
                                    <li>Répondre à cette réclamation via votre espace personnel</li>
                                    <li>Nous contacter directement par téléphone ou email</li>
                                    <li>Prendre rendez-vous avec le service concerné</li>
                                </ul>
                            </div>
                            
                            <!-- CTA Buttons -->
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ config('app.url') }}/espace-etudiant/reclamations/{{ $reclamation->id }}" style="display: inline-block; background-color: #3b82f6; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 6px; font-weight: 600; font-size: 15px; margin: 0 5px 10px 5px;">
                                    Voir les détails
                                </a>
                                <a href="{{ config('app.url') }}/espace-etudiant/reclamations/{{ $reclamation->id }}/repondre" style="display: inline-block; background-color: #10b981; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 6px; font-weight: 600; font-size: 15px; margin: 0 5px 10px 5px;">
                                    Répondre
                                </a>
                            </div>
                            
                            <!-- Satisfaction Survey (Optional) -->
                            <div style="background-color: #f3f4f6; border-radius: 4px; padding: 20px; margin: 25px 0; text-align: center;">
                                <p style="margin: 0 0 15px 0; color: #374151; font-size: 14px; font-weight: 600;">
                                    Êtes-vous satisfait de notre réponse ?
                                </p>
                                <div>
                                    <a href="{{ config('app.url') }}/feedback/{{ $reclamation->id }}/satisfied" style="display: inline-block; background-color: #10b981; color: #ffffff; text-decoration: none; padding: 8px 20px; border-radius: 4px; font-size: 13px; margin: 0 5px;">
                                        😊 Oui
                                    </a>
                                    <a href="{{ config('app.url') }}/feedback/{{ $reclamation->id }}/neutral" style="display: inline-block; background-color: #f59e0b; color: #ffffff; text-decoration: none; padding: 8px 20px; border-radius: 4px; font-size: 13px; margin: 0 5px;">
                                        😐 Moyen
                                    </a>
                                    <a href="{{ config('app.url') }}/feedback/{{ $reclamation->id }}/unsatisfied" style="display: inline-block; background-color: #ef4444; color: #ffffff; text-decoration: none; padding: 8px 20px; border-radius: 4px; font-size: 13px; margin: 0 5px;">
                                        😞 Non
                                    </a>
                                </div>
                            </div>
                            
                            <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin: 25px 0 0 0; text-align: center;">
                                Nous vous remercions pour votre confiance et restons à votre disposition.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px 40px; border-radius: 0 0 8px 8px; border-top: 1px solid #e5e7eb;">
                            <p style="color: #6b7280; font-size: 13px; line-height: 1.6; margin: 0 0 10px 0; text-align: center;">
                                <strong>Campus Admin Connect</strong><br>
                                Service des Réclamations & Assistance<br>
                                Tél : +212 5XX-XXXXXX | Email : reclamations@universite.ma<br>
                                Horaires : Lundi - Vendredi, 9h00 - 17h00
                            </p>
                            <p style="color: #9ca3af; font-size: 11px; margin: 15px 0 0 0; text-align: center;">
                                Cet email a été envoyé automatiquement, merci de ne pas y répondre.<br>
                                © {{ date('Y') }} Campus Admin Connect. Tous droits réservés.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>