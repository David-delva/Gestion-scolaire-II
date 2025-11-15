<?php
//
// Fichier : app/Models/TeacherModel.php
// Description : Modèle pour la gestion des enseignants.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Models;

use PDO;
use PDOException;

class TeacherModel
{
    private $db;
    private $userModel;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->userModel = new User(); // Pour interagir avec la table users
    }

    /**
     * Récupère tous les enseignants avec leurs informations utilisateur.
     *
     * @return array Un tableau de tous les enseignants.
     */
    public function getAllTeachers(): array
    {
        $sql = "SELECT t.id, t.user_id, t.phone, t.hire_date, u.first_name, u.last_name, u.email, r.name as role_name
                FROM teachers t
                JOIN users u ON t.user_id = u.id
                JOIN roles r ON u.role_id = r.id
                ORDER BY u.last_name, u.first_name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de tous les enseignants : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Récupère un enseignant par son ID.
     *
     * @param int $id L'ID de l'enseignant.
     * @return array|false Les données de l'enseignant ou false si non trouvé.
     */
    public function getTeacherById(int $id)
    {
        $sql = "SELECT t.id, t.user_id, t.phone, t.hire_date, u.first_name, u.last_name, u.email, u.role_id, r.name as role_name
                FROM teachers t
                JOIN users u ON t.user_id = u.id
                JOIN roles r ON u.role_id = r.id
                WHERE t.id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de l'enseignant par ID : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Récupère un enseignant par son ID utilisateur.
     *
     * @param int $userId L'ID de l'utilisateur.
     * @return array|false Les données de l'enseignant ou false si non trouvé.
     */
    public function getTeacherByUserId(int $userId)
    {
        $sql = "SELECT * FROM teachers WHERE user_id = :user_id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de l'enseignant par ID utilisateur : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Récupère les affectations (matières et classes) d'un enseignant spécifique.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @return array Un tableau des affectations de l'enseignant.
     */
    public function getTeacherAssignmentsByTeacherId(int $teacherId): array
    {
        $sql = "SELECT tsc.id, tsc.subject_id, tsc.class_id, s.name as subject_name, c.name as class_name
                FROM teacher_subject_class tsc
                JOIN subjects s ON tsc.subject_id = s.id
                JOIN classes c ON tsc.class_id = c.id
                WHERE tsc.teacher_id = :teacher_id
                ORDER BY c.name, s.name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des affectations de l'enseignant : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Ajoute un nouvel enseignant.
     *
     * @param string $email L'adresse email.
     * @param string $password Le mot de passe (non haché).
     * @param string $firstName Le prénom.
     * @param string $lastName Le nom de famille.
     * @param string $phone Le numéro de téléphone.
     * @param string $hireDate La date d'embauche.
     * @return int|false L'ID du nouvel enseignant ou false en cas d'échec.
     */
    public function addTeacher(string $email, string $password, string $firstName, string $lastName, string $phone, string $hireDate): bool
    {
        try {
            $this->db->beginTransaction();

            // 1. Créer l'utilisateur (rôle Enseignant)
            $teacherRoleId = $this->userModel->getRoleIdByName('Enseignant');
            if (!$teacherRoleId) {
                throw new PDOException("Rôle 'Enseignant' non trouvé.");
            }
            $userId = $this->userModel->createUser($email, $password, $firstName, $lastName, $teacherRoleId);

            if (!$userId) {
                throw new PDOException("Erreur lors de la création de l'utilisateur enseignant.");
            }

            // 2. Créer l'entrée dans la table teachers
            $sql = "INSERT INTO teachers (user_id, phone, hire_date) VALUES (:user_id, :phone, :hire_date)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'user_id' => $userId,
                'phone' => $phone,
                'hire_date' => $hireDate
            ]);

            $lastId = $this->db->lastInsertId();

            $this->db->commit();

            // Renvoyer true si l'ID est un nombre valide supérieur à 0
            return is_numeric($lastId) && $lastId > 0;

        } catch (PDOException $e) {
            $this->db->rollBack();
            log_message("Erreur lors de l'ajout de l'enseignant : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Met à jour un enseignant existant.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @param int $userId L'ID de l'utilisateur associé.
     * @param string $email La nouvelle adresse email.
     * @param string $firstName Le nouveau prénom.
     * @param string $lastName Le nouveau nom de famille.
     * @param string $phone Le nouveau numéro de téléphone.
     * @param string $hireDate La nouvelle date d'embauche.
     * @param string|null $newPassword Le nouveau mot de passe (non haché), ou null si inchangé.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function updateTeacher(int $teacherId, int $userId, string $email, string $firstName, string $lastName, string $phone, string $hireDate, ?string $newPassword = null): bool
    {
        try {
            $this->db->beginTransaction();

            // 1. Mettre à jour l'utilisateur
            $teacherRoleId = $this->userModel->getRoleIdByName('Enseignant');
            if (!$teacherRoleId) {
                throw new PDOException("Rôle 'Enseignant' non trouvé.");
            }
            $userUpdateSuccess = $this->userModel->updateUser($userId, $email, $firstName, $lastName, $teacherRoleId);

            if (!$userUpdateSuccess) {
                throw new PDOException("Erreur lors de la mise à jour de l'utilisateur enseignant.");
            }

            // 2. Mettre à jour le mot de passe si fourni
            if ($newPassword !== null && !empty($newPassword)) {
                $passwordUpdateSuccess = $this->userModel->updatePassword($userId, $newPassword);
                if (!$passwordUpdateSuccess) {
                    throw new PDOException("Erreur lors de la mise à jour du mot de passe de l'enseignant.");
                }
            }

            // 3. Mettre à jour l'entrée dans la table teachers
            $sql = "UPDATE teachers SET phone = :phone, hire_date = :hire_date WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $teacherUpdateSuccess = $stmt->execute([
                'phone' => $phone,
                'hire_date' => $hireDate,
                'id' => $teacherId
            ]);

            if (!$teacherUpdateSuccess) {
                throw new PDOException("Erreur lors de la mise à jour des informations de l'enseignant.");
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            log_message("Erreur lors de la mise à jour de l'enseignant : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Supprime un enseignant par son ID.
     *
     * @param int $id L'ID de l'enseignant à supprimer.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function deleteTeacher(int $id): bool
    {
        try {
            $this->db->beginTransaction();

            // Récupérer l'ID utilisateur associé à l'enseignant
            $teacher = $this->getTeacherById($id);
            if (!$teacher) {
                throw new PDOException("Enseignant non trouvé.");
            }
            $userId = $teacher['user_id'];

            // 1. Supprimer l'entrée dans la table teachers
            $sql = "DELETE FROM teachers WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $teacherDeleteSuccess = $stmt->execute(['id' => $id]);

            if (!$teacherDeleteSuccess) {
                throw new PDOException("Erreur lors de la suppression de l'entrée enseignant.");
            }

            // 2. Supprimer l'utilisateur associé
            $userDeleteSuccess = $this->userModel->deleteUser($userId);

            if (!$userDeleteSuccess) {
                throw new PDOException("Erreur lors de la suppression de l'utilisateur associé à l'enseignant.");
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            log_message("Erreur lors de la suppression de l'enseignant : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Récupère les matières uniques enseignées par un enseignant spécifique.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @return array Un tableau des matières uniques.
     */
    public function getSubjectsForTeacher(int $teacherId): array
    {
        $sql = "SELECT DISTINCT s.id, s.name
                FROM subjects s
                JOIN teacher_subject_class tsc ON s.id = tsc.subject_id
                WHERE tsc.teacher_id = :teacher_id
                ORDER BY s.name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des matières pour l'enseignant : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Récupère les classes uniques dans lesquelles un enseignant enseigne.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @return array Un tableau des classes uniques.
     */
    public function getClassesForTeacher(int $teacherId): array
    {
        $sql = "SELECT DISTINCT c.id, c.name
                FROM classes c
                JOIN teacher_subject_class tsc ON c.id = tsc.class_id
                WHERE tsc.teacher_id = :teacher_id
                ORDER BY c.name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Supprimer les doublons par ID
            $uniqueClasses = [];
            $seenIds = [];
            foreach ($classes as $class) {
                if (!in_array($class['id'], $seenIds)) {
                    $uniqueClasses[] = $class;
                    $seenIds[] = $class['id'];
                }
            }
            return $uniqueClasses;
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des classes pour l'enseignant : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Récupère les étudiants uniques dans les classes d'un enseignant avec leurs class_ids.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @return array Un tableau des étudiants uniques avec leurs class_ids.
     */
    public function getStudentsForTeacher(int $teacherId): array
    {
        $sql = "SELECT DISTINCT st.id, u.first_name, u.last_name, st.class_id
                FROM students st
                JOIN users u ON st.user_id = u.id
                WHERE st.class_id IN (
                    SELECT DISTINCT tsc.class_id
                    FROM teacher_subject_class tsc
                    WHERE tsc.teacher_id = :teacher_id
                )
                ORDER BY u.last_name, u.first_name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des étudiants pour l'enseignant : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Vérifie si un enseignant est autorisé à mettre une note pour un étudiant dans une matière donnée.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @param int $studentId L'ID de l'étudiant.
     * @param int $subjectId L'ID de la matière.
     * @return bool True si autorisé, false sinon.
     */
    public function isAllowedToAddGrade(int $teacherId, int $studentId, int $subjectId): bool
    {
        $sql = "SELECT COUNT(*)
                FROM teacher_subject_class tsc
                JOIN students s ON tsc.class_id = s.class_id
                WHERE tsc.teacher_id = :teacher_id
                  AND tsc.subject_id = :subject_id
                  AND s.id = :student_id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'teacher_id' => $teacherId,
                'subject_id' => $subjectId,
                'student_id' => $studentId
            ]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            log_message("Erreur lors de la vérification des permissions pour l'ajout de note : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Vérifie si un enseignant est autorisé à déclarer une absence pour un étudiant.
     * La vérification se base sur le fait que l'étudiant doit être dans une des classes de l'enseignant.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @param int $studentId L'ID de l'étudiant.
     * @return bool True si autorisé, false sinon.
     */
    public function isAllowedToAddAbsence(int $teacherId, int $studentId): bool
    {
        $sql = "SELECT COUNT(*)
                FROM students s
                WHERE s.id = :student_id
                  AND s.class_id IN (
                    SELECT DISTINCT tsc.class_id
                    FROM teacher_subject_class tsc
                    WHERE tsc.teacher_id = :teacher_id
                  )";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'teacher_id' => $teacherId,
                'student_id' => $studentId
            ]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            log_message("Erreur lors de la vérification des permissions pour l'ajout d'absence : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Crée une entrée enseignant à partir d'un ID utilisateur existant.
     *
     * @param int $userId L'ID de l'utilisateur.
     * @param string|null $phone Le numéro de téléphone.
     * @param string $hireDate La date d'embauche.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function createTeacherFromUser(int $userId, ?string $phone, string $hireDate): bool
    {
        $sql = "INSERT INTO teachers (user_id, phone, hire_date) VALUES (:user_id, :phone, :hire_date)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'user_id' => $userId,
                'phone' => $phone,
                'hire_date' => $hireDate
            ]);
        } catch (PDOException $e) {
            log_message("Érreur lors de la création de l'enseignant depuis l'utilisateur : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Récupère les étudiants avec toutes leurs informations pour un enseignant.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @return array Un tableau des étudiants avec leurs informations complètes.
     */
    public function getStudentsForTeacherWithDetails(int $teacherId): array
    {
        $sql = "SELECT DISTINCT st.id, st.student_id_number, st.date_of_birth, st.phone, st.class_id,
                       u.first_name, u.last_name, u.email
                FROM students st
                JOIN users u ON st.user_id = u.id
                WHERE st.class_id IN (
                    SELECT DISTINCT tsc.class_id
                    FROM teacher_subject_class tsc
                    WHERE tsc.teacher_id = :teacher_id
                )
                ORDER BY u.last_name, u.first_name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des étudiants avec détails pour l'enseignant : " . $e->getMessage(), "ERROR");
            return [];
        }
    }
}