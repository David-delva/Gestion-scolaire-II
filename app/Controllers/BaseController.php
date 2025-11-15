<?php
//
// Fichier : app/Controllers/BaseController.php
// Description : Contrôleur de base fournissant des fonctionnalités communes aux autres contrôleurs.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Controllers;

class BaseController
{
    /**
     * Charge une vue et la passe au layout principal.
     *
     * @param string $viewPath Le chemin de la vue à charger (ex: 'home/index').
     * @param array $data Les données à passer à la vue.
     */
    protected function render(string $viewPath, array $data = [])
    {
        // Démarrer la mise en mémoire tampon de la sortie
        ob_start();
        // Inclure la vue spécifique.
        require_once VIEWS_PATH . '/' . $viewPath . '.php';
        // Capturer le contenu de la vue
        $data['view_content'] = ob_get_clean();

        // Inclure le layout principal pour afficher le contenu
        require_once VIEWS_PATH . '/layout.php';
    }

    /**
     * Redirige l'utilisateur vers une URL donnée.
     *
     * @param string $url L'URL de destination.
     */
    protected function redirect(string $url)
    {
        // Assurer que l'URL de redirection est complète
        if (strpos($url, 'http://') !== 0 && strpos($url, 'https://') !== 0) {
            $url = BASE_URL . ltrim($url, '/');
        }
        
        // Journaliser la redirection pour le débogage
        log_message("Redirection vers : " . $url, "INFO");

        // Effectuer la redirection
        header('Location: ' . $url);
        exit();
    }

    /**
     * Définit un message d'alerte en session.
     *
     * @param string $type Le type d'alerte (success, danger, warning, info).
     * @param string $message Le message à afficher.
     */
    protected function setAlert(string $type, string $message)
    {
        $_SESSION['alert'] = ['type' => $type, 'message' => $message];
    }

    /**
     * Vérifie si l'utilisateur est connecté et a un rôle spécifique.
     *
     * @param string|array $requiredRole Le nom du rôle requis (ou un tableau de rôles).
     * @return bool True si l'utilisateur a le rôle requis, false sinon.
     */
    protected function hasRole($requiredRole): bool
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_name'])) {
            return false;
        }

        $userRole = $_SESSION['role_name'];

        if (is_array($requiredRole)) {
            return in_array($userRole, $requiredRole);
        } else {
            return $userRole === $requiredRole;
        }
    }

    /**
     * Vérifie si l'utilisateur est connecté et redirige si ce n'est pas le cas.
     */
    protected function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->setAlert('warning', 'Veuillez vous connecter pour accéder à cette page.');
            $this->redirect('auth/login');
        }
    }

    /**
     * Vérifie si l'utilisateur a le rôle requis et redirige si ce n'est pas le cas.
     *
     * @param string|array $requiredRole Le nom du rôle requis (ou un tableau de rôles).
     */
    protected function requireRole($requiredRole)
    {
        $this->requireLogin(); // Assurez-vous que l'utilisateur est connecté d'abord

        if (!$this->hasRole($requiredRole)) {
            $this->setAlert('danger', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
            $this->redirect(''); // Rediriger vers l'accueil ou une page d'erreur
        }
    }

    /**
     * Vérifie le token CSRF pour les requêtes POST
     */
    protected function validateCsrf()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                $this->setAlert('danger', 'Token de sécurité invalide.');
                $this->redirect($_SERVER['HTTP_REFERER'] ?? '');
            }
        }
    }
}
