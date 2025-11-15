<?php
//
// Fichier : public/index.php
// Description : Point d'entrée principal de l'application. Gère le routage des requêtes.
// Version : 1.0
// Date : Octobre 2025
//

// Inclure le fichier de configuration global de l'application.
require_once dirname(__DIR__) . '/config/config.php';

// --- Autoloading des classes (déjà configuré dans config.php) ---
// spl_autoload_register(function ($class_name) { ... });

// --- Gestion du routage ---

// Récupérer l'URL demandée et la nettoyer.
// Supprime le BASE_URL de l'URI pour obtenir le chemin relatif.
// Get the request URI relative to the script name
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];

// Remove the script name from the request URI
if (strpos($requestUri, $scriptName) === 0) {
    $requestUri = substr($requestUri, strlen($scriptName));
} elseif (strpos($requestUri, dirname($scriptName)) === 0) {
    // Handle cases where index.php is omitted but the directory is present
    $requestUri = substr($requestUri, strlen(dirname($scriptName)));
}

$requestUri = trim($requestUri, '/'); // Trim leading/trailing slashes

// Remove query string from URI
if (($pos = strpos($requestUri, '?')) !== false) {
    $requestUri = substr($requestUri, 0, $pos);
}

// Diviser l'URI en segments.
$segments = explode('/', $requestUri);

// Déterminer le contrôleur et l'action par défaut.
$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'HomeController'; // Par défaut HomeController
$actionName = !empty($segments[1]) ? $segments[1] : 'index'; // Par défaut index

// Les paramètres supplémentaires sont passés après l'action.
$params = array_slice($segments, 2);

// Valider le nom du contrôleur pour éviter les attaques de traversal de répertoire
if (!preg_match('/^[A-Za-z][A-Za-z0-9]*Controller$/', $controllerName)) {
    log_message("Nom de contrôleur invalide : " . $controllerName, "WARNING");
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 - Page non trouvée</h1>";
    exit;
}

// Construire le chemin complet du fichier contrôleur.
$controllerFile = CONTROLLERS_PATH . '/' . $controllerName . '.php';
$controllerClass = 'App\\Controllers\\' . $controllerName;

// Vérifier si le fichier contrôleur existe.
if (file_exists($controllerFile)) {
    require_once $controllerFile;

    // Vérifier si la classe du contrôleur existe.
    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();

        // Vérifier si la méthode (action) existe dans le contrôleur.
        if (method_exists($controller, $actionName)) {
            // Appeler la méthode avec les paramètres.
            call_user_func_array([$controller, $actionName], $params);
        } else {
            // Action non trouvée.
            log_message("Action non trouvée : " . $actionName . " dans le contrôleur " . $controllerName, "WARNING");
            // Gérer l'erreur 404 (par exemple, charger un contrôleur d'erreur ou une vue 404).
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 - Page non trouvée</h1><p>L'action \"" . htmlspecialchars($actionName) . "\" n'existe pas.</p>";
        }
    } else {
        // Classe contrôleur non trouvée.
        log_message("Classe contrôleur non trouvée : " . $controllerClass, "WARNING");
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Page non trouvée</h1><p>Le contrôleur \"" . htmlspecialchars($controllerName) . "\" n'existe pas.</p>";
    }
} else {
    // Fichier contrôleur non trouvé.
    log_message("Fichier contrôleur non trouvé : " . $controllerFile, "WARNING");
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 - Page non trouvée</h1><p>Le fichier contrôleur \"" . htmlspecialchars($controllerName) . ".php\" n'existe pas.</p>";
}