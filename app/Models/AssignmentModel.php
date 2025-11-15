<?php
//
// Fichier : app/Models/AssignmentModel.php
// Description : Modèle pour la gestion des affectations (enseignants-matières-classes et étudiants-classes).
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Models;

use PDO;
use PDOException;

class AssignmentModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // --- Gestion des affectations Enseignant-Matière-Classe ---

    /**
     * Récupère toutes les affectations enseignant-matière-classe.
     *
     * @return array Un tableau de toutes les affectations.
     */
    public function getAllTeacherAssignments(): array
    {
        $sql = "SELECT tsc.id, t.id as teacher_id, s.id as subject_id, c.id as class_id,
                       u.first_name as teacher_first_name, u.last_name as teacher_last_name,
                       s.name as subject_name, c.name as class_name
                FROM teacher_subject_class tsc
                JOIN teachers t ON tsc.teacher_id = t.id
                JOIN users u ON t.user_id = u.id
                JOIN subjects s ON tsc.subject_id = s.id
                JOIN classes c ON tsc.class_id = c.id
                ORDER BY c.name, s.name, u.last_name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des affectations enseignant-matière-classe : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Ajoute une affectation enseignant-matière-classe.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @param int $subjectId L'ID de la matière.
     * @param int $classId L'ID de la classe.
     * @return int|false L'ID de la nouvelle affectation ou false en cas d'échec.
     */
    public function addTeacherAssignment(int $teacherId, int $subjectId, int $classId)
    {
        $sql = "INSERT INTO teacher_subject_class (teacher_id, subject_id, class_id) VALUES (:teacher_id, :subject_id, :class_id)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'teacher_id' => $teacherId,
                'subject_id' => $subjectId,
                'class_id' => $classId
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            log_message("Erreur lors de l'ajout de l'affectation enseignant-matière-classe : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Supprime une affectation enseignant-matière-classe par son ID.
     *
     * @param int $id L'ID de l'affectation à supprimer.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function deleteTeacherAssignment(int $id): bool
    {
        $sql = "DELETE FROM teacher_subject_class WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            log_message("Erreur lors de la suppression de l'affectation enseignant-matière-classe : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Vérifie si une affectation enseignant-matière-classe existe déjà.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @param int $subjectId L'ID de la matière.
     * @param int $classId L'ID de la classe.
     * @return array|false Les données de l'affectation si elle existe, sinon false.
     */
    public function getTeacherAssignment(int $teacherId, int $subjectId, int $classId)
    {
        $sql = "SELECT * FROM teacher_subject_class WHERE teacher_id = :teacher_id AND subject_id = :subject_id AND class_id = :class_id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'teacher_id' => $teacherId,
                'subject_id' => $subjectId,
                'class_id' => $classId
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la vérification de l'affectation enseignant-matière-classe : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Récupère la classe d'un étudiant.
     *
     * @param int $studentId L'ID de l'étudiant.
     * @return array|false Les données de la classe ou false.
     */
    public function getStudentClassesByStudentId(int $studentId)
    {
        $sql = "SELECT s.class_id, c.name as class_name
                FROM students s
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE s.id = :student_id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['student_id' => $studentId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de la classe de l'étudiant : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Récupère les matières et les enseignants associés pour un étudiant.
     *
     * @param int $studentId L'ID de l'étudiant.
     * @return array Un tableau des matières et enseignants de l'étudiant.
     */
    public function getStudentSubjectsAndTeachers(int $studentId): array
    {
        $sql = "SELECT DISTINCT
                       s.name as subject_name,
                       c.name as class_name,
                       u_t.first_name as teacher_first_name,
                       u_t.last_name as teacher_last_name,
                       t.phone as teacher_phone
                FROM students st
                JOIN classes c ON st.class_id = c.id
                JOIN teacher_subject_class tsc ON c.id = tsc.class_id
                JOIN subjects s ON tsc.subject_id = s.id
                JOIN teachers t ON tsc.teacher_id = t.id
                JOIN users u_t ON t.user_id = u_t.id
                WHERE st.id = :student_id
                ORDER BY c.name, s.name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['student_id' => $studentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des matières et enseignants de l'étudiant : " . $e->getMessage(), "ERROR");
            return [];
        }
    }


}
