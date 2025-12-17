# 📧 Guide de Test - Envoi de PDF par Email

## ✅ Ce qui a été mis en place

### 1. Génération de PDF
- ✅ Installation de DomPDF (`barryvdh/laravel-dompdf`)
- ✅ Template Blade pour relevé de notes (`resources/views/pdf/releve_notes.blade.php`)
- ✅ Service PdfService avec méthodes pour tous les types de documents
- ✅ Route API pour téléchargement direct (`/api/demandes/download-pdf/{num_demande}`)

### 2. Envoi par Email
- ✅ Mise à jour du `EmailService` pour générer et attacher les PDFs
- ✅ Mise à jour du `DemandeValidee` Mailable pour gérer les pièces jointes
- ✅ Template email mis à jour pour mentionner les PDFs

### 3. Base de Données
- ✅ Migration pour rendre `decision_annee_id` nullable
- ✅ Seeder `CompleteStudentDataSeeder` créé pour ajouter notes et décisions

## 🎯 Comment Tester

### Étape 1: Vérifier les données

Exécutez cette commande pour créer des données de test complètes :

```bash
cd laravel
php artisan db:seed --class=CompleteStudentDataSeeder
```

Cette commande va :
- Créer des modules de base
- Ajouter des notes à toutes les inscriptions existantes
- Créer des décisions académiques pour chaque inscription
- Afficher le nombre de notes et décisions créées

### Étape 2: Vérifier la configuration email

Dans `.env`, assurez-vous que vos paramètres SMTP sont corrects :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@gmail.com
MAIL_PASSWORD=votre_mot_de_passe_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

⚠️ **Important pour Gmail** : Utilisez un "Mot de passe d'application" et non votre mot de passe normal
- Allez sur https://myaccount.google.com/apppasswords
- Créez un nouveau mot de passe d'application
- Utilisez-le dans `MAIL_PASSWORD`

### Étape  3: Créer une demande de relevé de notes

1. Ouvrez le frontend : http://localhost:5173
2. Allez sur "Demande de documents"
3. Remplissez avec les identifiants d'un étudiant qui a une décision (vérifiez avec la commande ci-dessus)
4. Sélectionnez "Relevé de notes"
5. Soumettez la demande

### Étape 4: Valider la demande (Admin)

1. Connectez-vous à l'interface admin
2. Allez dans "Demandes en attente"
3. Trouvez la demande créée
4. Cliquez sur "Valider"

### Étape 5: Vérifier l'envoi

Une fois la demande validée :

1. **Email** : L'étudiant reçoit un email avec le PDF en pièce jointe
2. **Logs** : Vérifiez les logs Laravel pour confirmer l'envoi
   ```bash
   tail -f storage/logs/laravel.log
   ```
3. **Storage** : Le PDF est sauvegardé dans `storage/app/documents/`

## 🐛 Dépannage

### Le PDF n'est pas généré

**Cause** : L'étudiant n'a pas de `DecisionAnnee` liée à son inscription

**Solution** :
```bash
php artisan db:seed --class=CompleteStudentDataSeeder
```

### L'email n'est pas envoyé

**Cause possible 1** : Configuration SMTP incorrecte
- Vérifiez votre fichier `.env`
- Testez avec : `php artisan tinker` puis `Mail::raw('Test', function($m) { $m->to('votre@email.com')->subject('Test'); });`

**Cause possible 2** : Firewall bloque le port 587
- Essayez le port 465 avec `MAIL_ENCRYPTION=ssl`

**Cause possible 3** : Gmail bloque l'accès
- Activez "Accès moins sécurisé" OU utilisez un mot de passe d'application

### Le PDF est vide ou malformé

**Cause** : Données manquantes dans le template
- Vérifiez que l'inscription a bien des notes
- Vérifiez les logs pour voir les erreurs de génération

## 📁 Fichiers Importants

- **Template PDF** : `laravel/resources/views/pdf/releve_notes.blade.php`
- **Service PDF** : `laravel/app/Services/PdfService.php`
- **Email Service** : `laravel/app/Services/EmailService.php`
- **Template Email** : `laravel/resources/views/emails/demande-validee.blade.php`
- **Mailable** : `laravel/app/Mail/DemandeValidee.php`
- **Controller** : `laravel/app/Http/Controllers/DemandeController.php` (méthode `downloadPdf`)

## 🎉 Résultat Attendu

Quand tout fonctionne correctement :

1. ✅ L'admin valide une demande
2. ✅ Le PDF est généré avec toutes les notes de l'étudiant
3. ✅ Le PDF est sauvegardé dans le storage
4. ✅ Un email est envoyé à l'étudiant avec le PDF en pièce jointe
5. ✅ L'étudiant peut aussi télécharger le PDF depuis la page de suivi

## 📞 Support

Si vous rencontrez des problèmes :
1. Vérifiez les logs : `storage/logs/laravel.log`
2. Vérifiez que les seeders ont bien créé les données
3. Testez la génération de PDF manuellement via tinker
4. Testez l'envoi d'email avec une commande simple

---

**Date de création** : 2025-12-17
**Dernière mise à jour** : 2025-12-17
