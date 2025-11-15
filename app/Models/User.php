<?php
//
// Fichier : app/Models/User.php
// Description : Modèle pour la gestion des utilisateurs et l'authentification.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Models;

use PDO;
use PDOException;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupère un utilisateur par son adresse email.
     *
     * @param string $email L'adresse email de l'utilisateur.
     * @return array|false Les données de l'utilisateur ou false si non trouvé.
     */
    public function getUserByEmail(string $email)
    {
        $sql = "SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = :email";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de l'utilisateur par email : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Récupère un utilisateur par son ID.
     *
     * @param int $id L'ID de l'utilisateur.
     * @return array|false Les données de l'utilisateur ou false si non trouvé.
     */
    public function getUserById(int $id)
    {
        $sql = "SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de l'utilisateur par ID : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Crée un nouvel utilisateur.
     *
     * @param string $email L'adresse email.
     * @param string $password Le mot de passe (non haché).
     * @param string $firstName Le prénom.
     * @param string $lastName Le nom de famille.
     * @param int $roleId L'ID du rôle.
     * @return int|false L'ID du nouvel utilisateur ou false en cas d'échec.
     */
    public function createUser(string $email, string $password, string $firstName, string $lastName, int $roleId)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (role_id, email, password, first_name, last_name) VALUES (:role_id, :email, :password, :first_name, :last_name)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'role_id' => $roleId,
                'email' => $email,
                'password' => $hashedPassword,
                'first_name' => $firstName,
                'last_name' => $lastName
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            log_message("Erreur lors de la création de l'utilisateur : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Récupère tous les utilisateurs avec leurs rôles.
     *
     * @return array Un tableau de tous les utilisateurs.
     */
    public function getAllUsers(): array
    {
        $sql = "SELECT u.id, u.email, u.first_name, u.last_name, u.role_id, u.is_active, r.name as role_name, u.created_at, u.updated_at FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.last_name, u.first_name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de tous les utilisateurs : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Active ou désactive un utilisateur.
     *
     * @param int $id L'ID de l'utilisateur.
     * @param bool $isActive Le nouveau statut.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function toggleUserStatus(int $id, bool $isActive): bool
    {
        $sql = "UPDATE users SET is_active = :is_active WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['is_active' => $isActive ? 1 : 0, 'id' => $id]);
        } catch (PDOException $e) {
            log_message("Erreur lors du changement de statut : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Supprime un utilisateur par son ID.
     *
     * @param int $id L'ID de l'utilisateur à supprimer.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function deleteUser(int $id): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            log_message("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Met à jour les informations d'un utilisateur (y compris le rôle).
     *
     * @param int $id L'ID de l'utilisateur.
     * @param string $email La nouvelle adresse email.
     * @param string $firstName Le nouveau prénom.
     * @param string $lastName Le nouveau nom de famille.
     * @param int $roleId Le nouvel ID du rôle.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function updateUser(int $id, string $email, string $firstName, string $lastName, int $roleId): bool
    {
        $sql = "UPDATE users SET email = :email, first_name = :first_name, last_name = :last_name, role_id = :role_id WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'role_id' => $roleId,
                'id' => $id
            ]);
        } catch (PDOException $e) {
            log_message("Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Met à jour le mot de passe d'un utilisateur.
     *
     * @param int $userId L'ID de l'utilisateur.
     * @param string $newPassword Le nouveau mot de passe (non haché).
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function updatePassword(int $userId, string $newPassword): bool
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'password' => $hashedPassword,
                'id' => $userId
            ]);
        } catch (PDOException $e) {
            log_message("Erreur lors de la mise à jour du mot de passe : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Vérifie les identifiants de connexion d'un utilisateur.
     *
     * @param string $email L'adresse email de l'utilisateur.
     * @param string $password Le mot de passe fourni.
     * @return array|false Les données de l'utilisateur si les identifiants sont valides, sinon false.
     */
    public function verifyCredentials(string $email, string $password)
    {
        $user = $this->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    /**
     * Récupère l'ID d'un rôle par son nom.
     *
     * @param string $roleName Le nom du rôle.
     * @return int|false L'ID du rôle ou false si non trouvé.
     */
    public function getRoleIdByName(string $roleName)
    {
        $sql = "SELECT id FROM roles WHERE name = :name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['name' => $roleName]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['id'] : false;
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de l'ID du rôle par nom : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Récupère tous les rôles disponibles.
     *
     * @return array Un tableau de tous les rôles.
     */
    public function getAllRoles(): array
    {
        $sql = "SELECT * FROM roles";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des rôles : " . $e->getMessage(), "ERROR");
            return [];
        }
    }
}