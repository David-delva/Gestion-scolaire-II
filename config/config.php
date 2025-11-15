<?php
//
// Fichier : config/config.php
// Description : Fichier de configuration général de l'application.
// Version : 1.0
// Date : Octobre 2025
//

// --- Configuration de l'affichage des erreurs ---
// En mode développement, afficher toutes les erreurs.
// En mode production, désactiver l'affichage des erreurs et les logger.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Définition des chemins de l'application ---
// Chemin absolu vers le répertoire racine du projet.
define('ROOT_PATH', dirname(__DIR__));

// Chemin absolu vers le répertoire 'app'.
define('APP_PATH', ROOT_PATH . '/app');

// Chemins vers les sous-répertoires de 'app'.
define('CONTROLLERS_PATH', APP_PATH . '/Controllers');
define('MODELS_PATH', APP_PATH . '/Models');
define('VIEWS_PATH', APP_PATH . '/Views');

// Chemin absolu vers le répertoire 'config'.
define('CONFIG_PATH', ROOT_PATH . '/config');

// Chemin absolu vers le répertoire 'public'.
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Chemin absolu vers le répertoire 'scripts'.
define('SCRIPTS_PATH', ROOT_PATH . '/scripts');

// --- Configuration de l'URL de base ---
// Détecte dynamiquement l'URL de base de l'application.
// Cela permet à l'application de fonctionner quel que soit le sous-répertoire où elle est installée.
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])); // Get directory of index.php
if ($basePath === '/') {
    $basePath = ''; // If it's root, no base path
}
define('BASE_URL', $protocol . "://" . $host . str_replace('/public', '', $basePath) . '/'); // Ensure trailing slash

// --- Configuration des sessions ---
// Démarrage de la session si elle n'est pas déjà démarrée.
if (session_status() == PHP_SESSION_NONE) {
    // Configuration des cookies de session pour la sécurité
    ini_set('session.cookie_httponly', 1); // Empêche l'accès au cookie via JavaScript
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS'])); // N'envoie le cookie que via HTTPS si disponible
    ini_set('session.use_strict_mode', 1); // Utilise le mode strict pour les sessions
    session_start();
}

// --- Configuration du fuseau horaire ---
date_default_timezone_set('Europe/Paris');

// --- Autres configurations (ex: clés API, paramètres spécifiques) ---
// define('API_KEY_EXAMPLE', 'votre_cle_api_ici');

// --- Fonction d'autoloading des classes ---
// Permet de charger automatiquement les classes lorsque l'on en a besoin.
spl_autoload_register(function ($class_name) {
    // Base namespace for the application
    $namespace = 'App';
    $base_dir = ROOT_PATH . '/app/';

    // Check if the class uses the base namespace
    if (strncmp($namespace, $class_name, strlen($namespace)) !== 0) {
        // If not, it's not a class from our application, let other autoloaders handle it
        return;
    }

    // Get the relative class name by removing the namespace prefix
    $relative_class = substr($class_name, strlen($namespace));

    // Formulate the file path
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Check if the file exists and load it
    if (file_exists($file)) {
        require_once $file;
    }
});

// --- Fonction de logging simple ---
// Pour enregistrer les erreurs ou informations importantes.
function log_message($message, $level = 'INFO') {
    $log_file = ROOT_PATH . '/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] [$level] $message" . PHP_EOL;
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// --- Génération de jeton CSRF ---
// Génère un jeton CSRF et le stocke en session.
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// --- Vérification de jeton CSRF ---
// Vérifie si le jeton CSRF soumis correspond à celui en session.
function verifyCsrfToken($token) {
    if (empty($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    return true;
}

// Générer un jeton CSRF au chargement de chaque page si nécessaire
generateCsrfToken();

// Appliquer les en-têtes de sécurité
if (class_exists('App\\Middleware\\SecurityMiddleware')) {
    App\Middleware\SecurityMiddleware::applySecurityHeaders();
}
