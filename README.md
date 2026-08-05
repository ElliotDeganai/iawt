# InAfrikaWeTrust — Squelette du projet

Incubateur web pour jeunes entrepreneurs africains (tourisme & agriculture).
Stack : **Laravel 10 + Vue 3 + Inertia.js + Laravel Breeze**, même stack que FreshFeed/CalTracker.

## Ce que contient ce squelette

- **Authentification complète** : inscription, connexion, mot de passe oublié/réinitialisation,
  vérification d'e-mail, confirmation de mot de passe.
- **Mon compte (profil)** : modification de l'identité (prénom, nom, e-mail, téléphone, pays, ville),
  upload d'avatar, changement de mot de passe, suppression de compte.
- **Système de rôles & permissions** custom (`roles`, `permissions`, `permission_role`) partagé côté
  front via `$page.props.auth.permissions` — même pattern que FreshFeed. Rôles pré-configurés :
  `admin` (toutes les permissions) et `moderator`. Les candidats/porteurs de projet n'ont pas de rôle
  back-office (`role_id = null`).
- Middlewares `admin` et `permission:slug` prêts pour protéger la future zone `/admin`.
- Palette de couleurs Tailwind reprise de la maquette (bordeaux `primary`, or `gold`, fond `cream`).
- Pages `Home.vue` et `Dashboard.vue` en placeholders, prêtes à être remplies dans les prochains blocs
  (design de la page d'accueil, parcours entrepreneur en 7 étapes, admin CMS, candidature, etc.).

## Installation

> ⚠️ Je n'ai pas pu exécuter `composer install` dans mon environnement (accès à packagist.org
> bloqué), donc le dossier `vendor/` n'est pas fourni. Voici comment démarrer chez toi.

```bash
# 1. Dépendances PHP
composer install

# 2. Dépendances JS
npm install

# 3. Environnement
cp .env.example .env
php artisan key:generate

# 4. Configure ta base de données dans .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
#    puis crée la base "inafrikawetrust" dans MySQL

# 5. Migrations + seeders (rôles, permissions, admin par défaut)
php artisan migrate --seed

# 6. Lien symbolique pour les avatars/médias publics
php artisan storage:link

# 7. Lancer le serveur
php artisan serve
npm run dev
```

## Compte admin par défaut (après le seed)

- **E-mail** : `admin@inafrikawetrust.org`
- **Mot de passe** : `password`

⚠️ À changer immédiatement en production.

## Prochains blocs à construire

1. Page d'accueil complète fidèle à la maquette (hero, programme, pays à l'honneur, stats, partenaires,
   témoignages).
2. Parcours entrepreneur en 7 étapes (idée → formalités) avec formulaires détaillés (le modèle
   "étude de marché" fourni dans le cahier des charges).
3. Page de candidature (formulaire complet avec upload CV/justificatif).
4. Compte personnel : webinaires, ressources documentaires, annuaires (prestataires/partenaires), forum.
5. Zone `/admin` (CRUD complet : utilisateurs, rôles, contenu, candidatures, pays à l'honneur, agenda,
   galerie) — sur le modèle de FreshFeed.
