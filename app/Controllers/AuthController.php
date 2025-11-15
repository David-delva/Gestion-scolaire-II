<?php
//
// Fichier : app/Controllers/AuthController.php
// Description : Contrôleur pour la gestion de l'authentification (connexion, déconnexion, profil).
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Controllers;

use App\Models\User;

class AuthController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function login()
    {
        // Si l'utilisateur est déjà connecté, le rediriger vers l'accueil ou son tableau de bord.
        if (isset($_SESSION['user_id'])) {
            $this->redirect(''); // Rediriger vers la page d'accueil par défaut
        }

        $data = [
            'title' => 'Connexion',
            'error' => '',
        ];

        // Si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW); // Le mot de passe n'est pas nettoyé car il sera haché

            if (empty($email) || empty($password)) {
                $data['error'] = 'Veuillez remplir tous les champs.';
            } else {
                $user = $this->userModel->verifyCredentials($email, $password);

                if ($user) {
                    // Vérifier si le compte est actif
                    if (!$user['is_active']) {
                        $data['error'] = 'Votre compte a été désactivé. Contactez l\'administrateur.';
                        log_message("Tentative de connexion d'un compte désactivé : " . $email, "WARNING");
                    } else {
                    // Authentification réussie, initialiser la session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_first_name'] = $user['first_name'];
                    $_SESSION['user_last_name'] = $user['last_name'];
                    $_SESSION['role_id'] = $user['role_id'];
                    $_SESSION['role_name'] = $user['role_name'];

                    log_message("Utilisateur " . $user['email'] . " connecté avec succès.", "INFO");
                    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Connexion réussie. Bienvenue !'];

                    // Redirection vers le tableau de bord approprié
                    switch ($user['role_name']) {
                        case 'Administrateur':
                            $this->redirect('admin/dashboard');
                            break;
                        case 'Enseignant':
                            $this->redirect('teacher/dashboard');
                            break;
                        case 'Étudiant':
                            $this->redirect('student/dashboard');
                            break;
                        default:
                            $this->redirect(''); // Page d'accueil par défaut
                            break;
                    }
                    }
                } else {
                    $data['error'] = 'Email ou mot de passe incorrect.';
                    log_message("Tentative de connexion échouée pour l'email : " . $email, "WARNING");
                }
            }
        }

        ob_start();
        require_once VIEWS_PATH . '/auth/login.php';
        $data['view_content'] = ob_get_clean();

        require_once VIEWS_PATH . '/layout.php';
    }

    /**
     * Déconnecte l'utilisateur et détruit la session.
     */
    public function logout()
    {
        if (isset($_SESSION['user_id'])) {
            log_message("Utilisateur " . $_SESSION['user_email'] . " déconnecté.", "INFO");
            session_unset();   // Supprime toutes les variables de session
            session_destroy(); // Détruit la session
            $_SESSION['alert'] = ['type' => 'info', 'message' => 'Vous avez été déconnecté.'];
        }
        $this->redirect('auth/login');
    }

    /**
     * Affiche et gère la mise à jour du profil utilisateur.
     */
    public function profile()
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $this->setAlert('warning', 'Veuillez vous connecter pour accéder à cette page.');
            $this->redirect('auth/login');
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            $this->setAlert('danger', 'Utilisateur non trouvé.');
            $this->redirect('auth/logout'); // Déconnecter si l'utilisateur n'existe plus
        }

        $data = [
            'title' => 'Mon Profil',
            'user' => $user,
            'error' => '',
            'success' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $currentPassword = filter_input(INPUT_POST, 'current_password', FILTER_UNSAFE_RAW);
            $newPassword = filter_input(INPUT_POST, 'new_password', FILTER_UNSAFE_RAW);
            $confirmNewPassword = filter_input(INPUT_POST, 'confirm_new_password', FILTER_UNSAFE_RAW);

            // Pour les étudiants, seuls email et mot de passe peuvent être modifiés
            if ($_SESSION['role_name'] === 'Étudiant') {
                $firstName = $user['first_name'];
                $lastName = $user['last_name'];
            } else {
                $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS);
                $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS);
            }

            // Validation des champs de profil
            if (empty($firstName) || empty($lastName) || empty($email)) {
                $data['error'] = 'Tous les champs obligatoires doivent être remplis.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'L\'adresse email n\'est pas valide.';
            } else {
                // Vérifier si l'email est déjà utilisé par un autre utilisateur
                $existingUser = $this->userModel->getUserByEmail($email);
                if ($existingUser && $existingUser['id'] !== $userId) {
                    $data['error'] = 'Cette adresse email est déjà utilisée par un autre compte.';
                } else {
                    // Mettre à jour les informations de base de l'utilisateur
                    if ($this->userModel->updateUser($userId, $email, $firstName, $lastName, $user['role_id'])) {
                        // Mettre à jour la session
                        $_SESSION['user_email'] = $email;
                        $_SESSION['user_first_name'] = $firstName;
                        $_SESSION['user_last_name'] = $lastName;
                        $data['success'] = 'Profil mis à jour avec succès.';
                        log_message("Profil utilisateur " . $userId . " mis à jour.", "INFO");
                    } else {
                        $data['error'] = 'Erreur lors de la mise à jour du profil.';
                    }

                    // Gérer le changement de mot de passe si les champs sont remplis
                    if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmNewPassword)) {
                        if (!password_verify($currentPassword, $user['password'])) {
                            $data['error'] .= (empty($data['error']) ? '' : '<br>') . 'Le mot de passe actuel est incorrect.';
                        } elseif ($newPassword !== $confirmNewPassword) {
                            $data['error'] .= (empty($data['error']) ? '' : '<br>') . 'Le nouveau mot de passe et sa confirmation ne correspondent pas.';
                        } elseif (strlen($newPassword) < 8) {
                            $data['error'] .= (empty($data['error']) ? '' : '<br>') . 'Le nouveau mot de passe doit contenir au moins 8 caractères.';
                        } else {
                            if ($this->userModel->updatePassword($userId, $newPassword)) {
                                $data['success'] .= (empty($data['success']) ? '' : '<br>') . 'Mot de passe mis à jour avec succès.';
                                log_message("Mot de passe utilisateur " . $userId . " mis à jour.", "INFO");
                            } else {
                                $data['error'] .= (empty($data['error']) ? '' : '<br>') . 'Erreur lors de la mise à jour du mot de passe.';
                            }
                        }
                    }
                }
            }
            // Recharger les données utilisateur après la mise à jour pour refléter les changements
            $data['user'] = $this->userModel->getUserById($userId);
        }

        $this->render('auth/profile', $data);
    }
}