# cPanel Manager

Panel d'administration interne développé **100 % from scratch** sur Laravel 13.  
Permet de gérer un compte cPanel via API sans jamais exposer l'interface cPanel directement.

**Accès technicien** : authentification **exclusivement via Google OAuth** (pas de formulaire email/mot de passe).

Design **mode clair** avec la charte graphique **Groupe Speed Cloud** (police Titillium Web, couleur accent `#8a4dfd`).

---

## Fonctionnalités

| Module | Actions disponibles |
|---|---|
| **E-mails** | Créer, supprimer, réinitialiser le mot de passe, gérer les redirections |
| **Bases de données** | Créer une base MySQL, créer un utilisateur, assigner des privilèges |
| **Domaines** | Ajouter un domaine addon, créer un sous-domaine |
| **FTP** | Créer et supprimer des comptes FTP |
| **Cron Jobs** | Créer et supprimer des tâches planifiées |
| **Statistiques** | Disque, bande passante, ressources globales |
| **Utilisateurs** | Créer des techniciens, gérer leurs permissions fine-grained |
| **Journaux** | Historique complet de toutes les actions avec filtres avancés |

---

## Architecture

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── UserController.php
│   │   ├── PermissionController.php
│   │   ├── EmailController.php
│   │   ├── DatabaseController.php
│   │   ├── DomainController.php
│   │   ├── FtpController.php
│   │   ├── CronController.php
│   │   ├── StatsController.php
│   │   └── LogController.php
│   └── Middleware/
│       ├── Authenticate.php       — Vérifie session + statut actif
│       ├── CheckPermission.php    — Contrôle permission avant chaque action
│       └── SecurityHeaders.php   — Headers HTTP sécurisés sur toutes les réponses
├── Models/
│   ├── User.php
│   ├── Permission.php
│   ├── UserPermission.php
│   └── ActionLog.php
└── Services/
    ├── CpanelService.php          — Gateway unique vers l'API cPanel (UAPI + API2)
    ├── PermissionService.php      — Vérification avec cache, invalidation automatique
    └── LoggerService.php          — Journalisation complète de toutes les actions
```

---

## Prérequis

- PHP 8.2+
- Composer
- MySQL 8+ (ou MariaDB 10.4+)
- Un compte cPanel avec un **Personal Access Token** généré
- Un projet **Google Cloud** avec les identifiants **OAuth 2.0** configurés

---

## Installation

### 1. Cloner et installer les dépendances

```bash
git clone https://github.com/mponsart/cpanelmanager.git
cd cpanelmanager
composer install --no-dev --optimize-autoloader
```

### 2. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Éditer le fichier `.env` et renseigner **obligatoirement** :

```env
APP_URL=https://votre-panel.example.com
APP_DEBUG=false

DB_HOST=127.0.0.1
DB_DATABASE=cpanelmanager
DB_USERNAME=db_user
DB_PASSWORD=mot_de_passe_fort

CPANEL_HOST=votre-serveur.example.com
CPANEL_PORT=2083
CPANEL_USERNAME=nom_du_compte_cpanel
CPANEL_TOKEN=token_api_personnel_cpanel
CPANEL_DOMAIN=example.com

GOOGLE_CLIENT_ID=xxxxxxxxxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xxxxxxxxxxxxxxxxxxxxxxxx
GOOGLE_REDIRECT_URI=https://votre-panel.example.com/auth/google/callback

ADMIN_EMAIL=admin@example.com
```

> **Comment obtenir le token cPanel ?**  
> Connexion cPanel → Sécurité → **Gestion des tokens API** → Créer un token.

> **Comment configurer Google OAuth ?**  
> 1. Aller sur [Google Cloud Console](https://console.cloud.google.com/apis/credentials)  
> 2. Créer un projet (ou sélectionner un existant)  
> 3. **API et services → Identifiants → Créer des identifiants → ID client OAuth 2.0**  
> 4. Type : **Application Web**  
> 5. Ajouter l'URI de redirection autorisée : `https://votre-panel.example.com/auth/google/callback`  
> 6. Copier le **Client ID** et le **Client Secret** dans le `.env`  
> 7. **Écran de consentement OAuth** : configurer en mode « Interne » (réservé à votre organisation Google Workspace) ou ajouter les utilisateurs test en mode « Externe »

### 3. Base de données

```bash
php artisan migrate
php artisan db:seed
```

Le seeder crée automatiquement :
- Les **12 permissions** système
- Un **compte super administrateur** avec l'adresse `ADMIN_EMAIL` définie dans `.env` (par défaut : `maxime.ponsart@groupe-speed.cloud`)

### 4. Se connecter

Accédez à l'URL du panel. Cliquez sur **« Se connecter avec Google »** et authentifiez-vous avec un compte Google dont l'adresse e-mail correspond à un utilisateur créé lors du seed.

> **Note :** Seuls les comptes pré-enregistrés dans la base peuvent se connecter. Un compte Google inconnu sera refusé.

---

## Gestion du super administrateur

Par défaut, le super admin est `maxime.ponsart@groupe-speed.cloud`. Pour changer ou ajouter des super admins, modifier le `.env` :

```env
# Super administrateur principal (créé au seed)
ADMIN_EMAIL=maxime.ponsart@groupe-speed.cloud
ADMIN_NAME=Maxime Ponsart

# Ajouter d'autres super admins (optionnel, emails séparés par des virgules)
FORCE_SUPER_ADMINS=autre.technicien@groupe-speed.cloud,collegue@exemple.com
```

Puis relancer le seeder :

```bash
php artisan db:seed --class=AdminUserSeeder
```

Le seeder utilise `updateOrCreate` : il crée les comptes s'ils n'existent pas, ou met à jour le flag `is_super_admin` s'ils existent déjà. Aucune donnée n'est supprimée.

> **Astuce :** Pour promouvoir un technicien existant en super admin, ajoutez simplement son email dans `FORCE_SUPER_ADMINS` et relancez le seeder.

---

## Permissions disponibles

| Clé | Description | Module |
|---|---|---|
| `manage_users` | Créer et gérer les techniciens | admin |
| `view_email` | Voir la liste des adresses e-mail | email |
| `create_email` | Créer/modifier des adresses e-mail | email |
| `delete_email` | Supprimer une adresse e-mail | email |
| `view_db` | Lister les bases de données MySQL | database |
| `create_db` | Créer bases, utilisateurs, privilèges | database |
| `view_domain` | Lister les domaines | domain |
| `create_domain` | Ajouter domaines et sous-domaines | domain |
| `view_ftp` | Lister les comptes FTP | ftp |
| `create_ftp` | Créer/supprimer des comptes FTP | ftp |
| `manage_cron` | Gérer les tâches planifiées | cron |
| `view_stats` | Consulter les statistiques | stats |

---

## Sécurité

- **Authentification Google OAuth 2.0** — Aucun mot de passe stocké, connexion exclusivement via Google
- **Contrôle d'accès** — Seuls les comptes techniciens pré-enregistrés peuvent se connecter
- **CSRF** — Protection sur tous les formulaires
- **Rate limiting** — Throttle sur les routes sensibles
- **Régénération de session** — Après chaque connexion réussie (anti-fixation)
- **Validation stricte** — `email:rfc,dns`, regex sur noms de bases/comptes FTP/cron
- **Anti-injection cron** — Blocage des caractères `;`, `&`, `|`, `` ` ``, `$`, `>`, `<`
- **Headers HTTP** — CSP, HSTS, X-Frame-Options: DENY, X-Content-Type-Options, suppression des headers techniques
- **Redaction des logs** — Les mots de passe sont automatiquement remplacés par `[REDACTED]` dans les payloads journalisés
- **Isolation totale** — Le token cPanel n'est jamais transmis au frontend ni affiché dans les logs

---

## Structure des journaux

Chaque action enregistre :

| Champ | Description |
|---|---|
| `user_id` | Technicien à l'origine de l'action |
| `action` | Identifiant de l'action (`create_email`, `delete_ftp`…) |
| `module` | Module concerné (`email`, `database`…) |
| `target` | Cible de l'action (adresse e-mail, nom de domaine…) |
| `payload` | Paramètres envoyés (mots de passe redactés) |
| `ip` | Adresse IP du client |
| `user_agent` | Navigateur/client utilisé |
| `status` | `success`, `error` ou `denied` |
| `error_message` | Message d'erreur si applicable |
| `created_at` | Horodatage précis |

---

## Déploiement en production

```bash
# Optimiser l'autoloader et les caches
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions des dossiers
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

Configurer le serveur web (Nginx/Apache) pour pointer sur le dossier `public/`.  
**Ne jamais exposer** le dossier racine du projet.

---

## Stack technique

- **Laravel 13** — Framework MVC uniquement, sans package admin
- **Laravel Socialite** — Authentification Google OAuth 2.0
- **PHP 8.4** — Typage strict, propriétés readonly, enums natifs
- **SQLite / MySQL** — Base de données (SQLite par défaut en dev)
- **cURL / Laravel HTTP Client** — Appels API cPanel via UAPI et API2
- **Titillium Web** — Police de la charte Groupe Speed Cloud
- **Interface mode clair** — Design épuré, couleur accent `#8a4dfd`

