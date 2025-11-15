<?php
//
// Fichier : config/database.php
// Description : Fichier de configuration des paramètres de connexion à la base de données.
// Version : 1.0
// Date : Octobre 2025
//

// --- Paramètres de connexion à la base de données MySQL ---

define('DB_HOST', 'localhost');
define('DB_NAME', 'gestion_scolaire'); // Nom de votre base de données
define('DB_USER', 'root');             // Votre nom d'utilisateur MySQL
define('DB_PASS', '');                 // Votre mot de passe MySQL
define('DB_CHARSET', 'utf8mb4');

// Options PDO pour une connexion sécurisée et robuste
// Ces options sont passées au constructeur PDO.
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Active le mode exception pour les erreurs
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Définit le mode de récupération par défaut à associatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Désactive l'émulation des requêtes préparées pour une meilleure sécurité
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '" . DB_CHARSET . "' COLLATE '" . DB_CHARSET . "_unicode_ci'" // Définit l'encodage des caractères
]);
