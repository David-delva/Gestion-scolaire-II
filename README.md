# Projet de Gestion des Étudiants

Ce projet est une application web complète de gestion des étudiants, développée en PHP 8+, MySQL 8, JavaScript ES6, HTML5, CSS3 et Bootstrap 5.3. Il suit une architecture MVC stricte pour garantir la propreté, la maintenabilité et la sécurité du code.

## GES — Prototype de Gestion des Étudiants

Ce dépôt contient une application web simple de gestion d'un établissement scolaire (Gestion des Étudiants — GES).
Technos principales : PHP 8+, PDO/MySQL, JavaScript ES6, Bootstrap 5, HTML5 et CSS3.

Cette version suit une architecture légère de type MVC, avec des contrôleurs dans `app/Controllers`, la logique métier dans `app/Models`, et les vues dans `app/Views`.

## Sommaire
- Présentation
- Prérequis
- Installation & démarrage rapide (Windows / bash)
- Structure du projet (résumé par dossier)
- Base de données (remarques & import)
- Sécurité et bonnes pratiques
- Identifiants de test
- Points à corriger / recommandations

---

## Présentation

GES permet de gérer les utilisateurs (administrateurs, enseignants, étudiants), les classes, les matières, les affectations, les notes et les absences. Le code est organisé pour être simple à comprendre et à étendre.

## Prérequis

- PHP 8.0+ avec extensions : pdo_mysql, mbstring, json
- MySQL 8 (ou compatible)
- Un serveur web (Apache/Nginx) ou le serveur PHP intégré pour du développement
- Git (optionnel)

Remarque : le projet utilise PDO avec des requêtes préparées et BCrypt pour le hachage des mots de passe.

## Installation & démarrage rapide (Windows, bash.exe)

1) Copier le projet dans votre dossier de travail ou cloner le dépôt :

```bash
git clone <URL_DU_DEPOT> gestion-etudiants
cd gestion-etudiants
```

2) Configuration de la base de données :

- Le fichier `schema.sql` crée les tables et insère des données de test. Par défaut le schéma utilise la base `gestion_scolaire` (cf. `schema.sql`).
- Avant d'importer, vérifiez le fichier `config/database.php` et ajustez `DB_NAME`, `DB_USER`, `DB_PASS` si nécessaire.

Import MySQL (depuis bash) :

```bash
mysql -u root -p < schema.sql
```

Remarque importante : `schema.sql` contient des segments SQL redondants et des instructions compatibles SQL Server (ex: `ALTER TABLE ... WITH VALUES`) — voir la section "Points à corriger" ci-dessous. Avant import sur MySQL, supprimez ou modifiez ces lignes si l'import échoue.

3) Configuration de l'application :

- Vérifiez `config/database.php` pour les paramètres DB.
- Par défaut le projet définit `BASE_URL` automatiquement dans `config/config.php`. Si vous utilisez un VirtualHost (recommandé), pointez-le vers le dossier `public/`.

4) Lancer localement (serveur PHP intégré — utile pour développement) :

```bash
# Depuis la racine du projet
php -S localhost:8000 -t public

# Puis ouvrir http://localhost:8000
```

## Structure du projet (résumé)

- `app/Controllers/` : contrôleurs (AdminController, AuthController, TeacherController, StudentController, ...)
- `app/Models/` : modèles et accès DB (Database singleton, User, TeacherModel, StudentModel, GradeModel, AbsenceModel, AssignmentModel, StatsModel, ...)
- `app/Views/` : vues PHP (layout + dossiers par rôle)
- `app/Utils/Validator.php` : validations réutilisables côté serveur
- `app/Middleware/SecurityMiddleware.php` : en-têtes de sécurité et rate limiting basique
- `config/` : `config.php` (autoload, sessions, CSRF, constantes) et `database.php` (constantes PDO)
- `public/` : point d'entrée `index.php`, assets (CSS/JS/images)
- `scripts/` : scripts JS additionnels
- `schema.sql` : schéma et données de test

## Base de données — remarques importantes

- Base définie dans `schema.sql` : `gestion_scolaire` (vérifiez `DB_NAME` dans `config/database.php`).
- `schema.sql` contient des INSERTs et des constraints utiles. Toutefois :
  - Il existe des duplications d'instructions `ALTER TABLE` (répétées deux fois).
  - Certaines commandes (`WITH VALUES`, constructions `CONSTRAINT ... DEFAULT (...) WITH VALUES`) sont spécifiques à SQL Server et provoqueront des erreurs sur MySQL. Il faudra les supprimer ou les adapter avant import.

Conseil : importer uniquement les CREATE TABLE et les INSERTs, puis appliquer manuellement les contraintes additionnelles compatibles MySQL.

## Sécurité et bonnes pratiques

- Les mots de passe sont hachés via password_hash (BCRYPT).
- Les formulaires vérifient un token CSRF généré par `config/config.php`.
- Les requêtes utilisent PDO + requêtes préparées.
- `SecurityMiddleware::applySecurityHeaders()` fixe CSP, X-Frame-Options, etc. Vérifier les sources autorisées selon votre déploiement.

## Identifiants de test (extraits de `schema.sql`)

- Admin : `admin@example.com` / password
- Professeurs : `prof.math@example.com`, `prof.fr@example.com` / password
- Étudiants : `etudiant1@example.com`, `etudiant2@example.com` / password

Changez impérativement ces mots de passe en production.

## Points à corriger / recommandations

1. Harmoniser le nom de base de données dans la doc (`gestion_etudiants` vs `gestion_scolaire`) — le fichier `config/database.php` utilise `gestion_scolaire`.
2. Nettoyer `schema.sql` : supprimer les instructions SQL Server incompatibles et les doublons (rechercher `WITH VALUES` et les blocs ALTER répétés).
3. Ajouter (optionnel) un fichier `.env` et charger les paramètres via une petite librairie ou un loader pour éviter de committer les credentials.
4. Ajouter des tests unitaires basiques (par exemple pour `Validator` et les méthodes critiques des modèles).
5. Vérifier le comportement des sessions en environnement HTTPS et configurer correctement `session.cookie_secure` en production.

## Vérifications rapides et debug

- Vérifier les logs dans `app.log` si des erreurs PDO surviennent.
- En cas d'erreur 404 depuis `public/index.php`, vérifier que `CONTROLLERS_PATH` et `BASE_URL` sont corrects.

Bon développement !