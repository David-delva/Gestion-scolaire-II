<?php
//
// Fichier : app/Models/StudentModel.php
// Description : Modèle pour la gestion des étudiants.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Models;

use PDO;
use PDOException;

class StudentModel
{
    private $db;
    private $userModel;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->userModel = new User(); // Pour interagir avec la table users
    }

    /**
     * Récupère un étudiant par son ID utilisateur.
     *
     * @param int $userId L'ID de l'utilisateur.
     * @return array|false Les données de l'étudiant ou false si non trouvé.
     */
    public function getStudentByUserId(int $userId)
    {
        $sql = "SELECT * FROM students WHERE user_id = :user_id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de l'étudiant par ID utilisateur : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Récupère tous les étudiants avec leurs informations utilisateur.
     *
     * @return array Un tableau de tous les étudiants.
     */
    public function getAllStudents(): array
    {
        $sql = "SELECT s.id, s.user_id, s.student_id_number, s.class_id, s.date_of_birth, s.address, s.phone, u.first_name, u.last_name, u.email, r.name as role_name, c.name as class_name
                FROM students s
                JOIN users u ON s.user_id = u.id
                JOIN roles r ON u.role_id = r.id
                LEFT JOIN classes c ON s.class_id = c.id
                ORDER BY u.last_name, u.first_name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de tous les étudiants : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Récupère un étudiant par son ID.
     *
     * @param int $id L'ID de l'étudiant.
     * @return array|false Les données de l'étudiant ou false si non trouvé.
     */
    public function getStudentById(int $id)
    {
        $sql = "SELECT s.id, s.user_id, s.student_id_number, s.class_id, s.date_of_birth, s.address, s.phone, u.first_name, u.last_name, u.email, u.role_id, r.name as role_name, c.name as class_name
                FROM students s
                JOIN users u ON s.user_id = u.id
                JOIN roles r ON u.role_id = r.id
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE s.id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de l'étudiant par ID : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Ajoute un nouvel étudiant.
     *
     * @param string $email L'adresse email.
     * @param string $password Le mot de passe (non haché).
     * @param string $firstName Le prénom.
     * @param string $lastName Le nom de famille.
     * @param string $studentIdNumber Le numéro d'identification de l'étudiant.
     * @param int|null $classId L'ID de la classe.
     * @param string $dateOfBirth La date de naissance.
     * @param string|null $address L'adresse.
     * @param string|null $phone Le numéro de téléphone.
     * @return int|false L'ID du nouvel étudiant ou false en cas d'échec.
     */
    public function addStudent(string $email, string $password, string $firstName, string $lastName, string $studentIdNumber, ?int $classId, string $dateOfBirth, ?string $address = null, ?string $phone = null): bool
    {
        try {
            $this->db->beginTransaction();

            // 1. Créer l'utilisateur (rôle Étudiant)
            $studentRoleId = $this->userModel->getRoleIdByName('Étudiant');
            if (!$studentRoleId) {
                throw new PDOException("Rôle 'Étudiant' non trouvé.");
            }
            $userId = $this->userModel->createUser($email, $password, $firstName, $lastName, $studentRoleId);

            if (!$userId) {
                throw new PDOException("Erreur lors de la création de l'utilisateur étudiant.");
            }

            // 2. Créer l'entrée dans la table students
            $sql = "INSERT INTO students (user_id, student_id_number, class_id, date_of_birth, address, phone) VALUES (:user_id, :student_id_number, :class_id, :date_of_birth, :address, :phone)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'user_id' => $userId,
                'student_id_number' => $studentIdNumber,
                'class_id' => $classId,
                'date_of_birth' => $dateOfBirth,
                'address' => $address,
                'phone' => $phone
            ]);

            $lastId = $this->db->lastInsertId();

            $this->db->commit();
            
            // Renvoyer true si l'ID est un nombre valide supérieur à 0
            return is_numeric($lastId) && $lastId > 0;

        } catch (PDOException $e) {
            $this->db->rollBack();
            log_message("Erreur lors de l'ajout de l'étudiant : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Met à jour un étudiant existant.
     *
     * @param int $studentId L'ID de l'étudiant.
     * @param int $userId L'ID de l'utilisateur associé.
     * @param string $email La nouvelle adresse email.
     * @param string $firstName Le nouveau prénom.
     * @param string $lastName Le nouveau nom de famille.
     * @param string $studentIdNumber Le nouveau numéro d'identification de l'étudiant.
     * @param int|null $classId L'ID de la classe.
     * @param string $dateOfBirth La nouvelle date de naissance.
     * @param string|null $address La nouvelle adresse.
     * @param string|null $phone Le nouveau numéro de téléphone.
     * @param string|null $newPassword Le nouveau mot de passe (non haché), ou null si inchangé.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function updateStudent(int $studentId, int $userId, string $email, string $firstName, string $lastName, string $studentIdNumber, ?int $classId, string $dateOfBirth, ?string $address = null, ?string $phone = null, ?string $newPassword = null): bool
    {
        try {
            $this->db->beginTransaction();

            // 1. Mettre à jour l'utilisateur
            $studentRoleId = $this->userModel->getRoleIdByName('Étudiant');
            if (!$studentRoleId) {
                throw new PDOException("Rôle 'Étudiant' non trouvé.");
            }
            $userUpdateSuccess = $this->userModel->updateUser($userId, $email, $firstName, $lastName, $studentRoleId);

            if (!$userUpdateSuccess) {
                throw new PDOException("Erreur lors de la mise à jour de l'utilisateur étudiant.");
            }

            // 2. Mettre à jour le mot de passe si fourni
            if ($newPassword !== null && !empty($newPassword)) {
                $passwordUpdateSuccess = $this->userModel->updatePassword($userId, $newPassword);
                if (!$passwordUpdateSuccess) {
                    throw new PDOException("Erreur lors de la mise à jour du mot de passe de l'étudiant.");
                }
            }

            // 3. Mettre à jour l'entrée dans la table students
            $sql = "UPDATE students SET student_id_number = :student_id_number, class_id = :class_id, date_of_birth = :date_of_birth, address = :address, phone = :phone WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $studentUpdateSuccess = $stmt->execute([
                'student_id_number' => $studentIdNumber,
                'class_id' => $classId,
                'date_of_birth' => $dateOfBirth,
                'address' => $address,
                'phone' => $phone,
                'id' => $studentId
            ]);

            if (!$studentUpdateSuccess) {
                throw new PDOException("Erreur lors de la mise à jour des informations de l'étudiant.");
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            log_message("Erreur lors de la mise à jour de l'étudiant : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Supprime un étudiant par son ID.
     *
     * @param int $id L'ID de l'étudiant à supprimer.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function deleteStudent(int $id): bool
    {
        try {
            $this->db->beginTransaction();

            // Récupérer l'ID utilisateur associé à l'étudiant
            $student = $this->getStudentById($id);
            if (!$student) {
                throw new PDOException("Étudiant non trouvé.");
            }
            $userId = $student['user_id'];

            // 1. Supprimer l'entrée dans la table students
            $sql = "DELETE FROM students WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $studentDeleteSuccess = $stmt->execute(['id' => $id]);

            if (!$studentDeleteSuccess) {
                throw new PDOException("Erreur lors de la suppression de l'entrée étudiant.");
            }

            // 2. Supprimer l'utilisateur associé
            $userDeleteSuccess = $this->userModel->deleteUser($userId);

            if (!$userDeleteSuccess) {
                throw new PDOException("Erreur lors de la suppression de l'utilisateur associé à l'étudiant.");
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            log_message("Erreur lors de la suppression de l'étudiant : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Vérifie si un numéro d'identification étudiant existe déjà.
     *
     * @param string $studentIdNumber Le numéro d'identification étudiant.
     * @param int|null $excludeId L'ID de l'étudiant à exclure de la vérification (pour les mises à jour).
     * @return array|false Les données de l'étudiant si le numéro existe, sinon false.
     */
    public function getStudentByStudentIdNumber(string $studentIdNumber, ?int $excludeId = null)
    {
        $sql = "SELECT * FROM students WHERE student_id_number = :student_id_number";
        $params = ['student_id_number' => $studentIdNumber];

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la vérification du numéro d'identification étudiant : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Crée une entrée étudiant à partir d'un ID utilisateur existant.
     *
     * @param int $userId L'ID de l'utilisateur.
     * @param string $studentIdNumber Le numéro d'identification de l'étudiant.
     * @param int|null $classId L'ID de la classe.
     * @param string $dateOfBirth La date de naissance.
     * @param string|null $address L'adresse.
     * @param string|null $phone Le numéro de téléphone.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function createStudentFromUser(int $userId, string $studentIdNumber, ?int $classId, string $dateOfBirth, ?string $address, ?string $phone): bool
    {
        $sql = "INSERT INTO students (user_id, student_id_number, class_id, date_of_birth, address, phone) VALUES (:user_id, :student_id_number, :class_id, :date_of_birth, :address, :phone)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'user_id' => $userId,
                'student_id_number' => $studentIdNumber,
                'class_id' => $classId,
                'date_of_birth' => $dateOfBirth,
                'address' => $address,
                'phone' => $phone
            ]);
        } catch (PDOException $e) {
            log_message("Érreur lors de la création de l'étudiant depuis l'utilisateur : " . $e->getMessage(), "ERROR");
            return false;
        }
    }
}