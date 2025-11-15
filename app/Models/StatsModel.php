<?php
//
// Fichier : app/Models/StatsModel.php
// Description : Modèle pour la récupération des statistiques globales de l'application.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Models;

use PDO;
use PDOException;

class StatsModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupère le nombre total d'utilisateurs.
     *
     * @return int Le nombre total d'utilisateurs.
     */
    public function getTotalUsers(): int
    {
        $sql = "SELECT COUNT(*) FROM users";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération du nombre total d'utilisateurs : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    /**
     * Récupère le nombre total d'étudiants.
     *
     * @return int Le nombre total d'étudiants.
     */
    public function getTotalStudents(): int
    {
        $sql = "SELECT COUNT(*) FROM students";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération du nombre total d'étudiants : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    /**
     * Récupère le nombre total d'enseignants.
     *
     * @return int Le nombre total d'enseignants.
     */
    public function getTotalTeachers(): int
    {
        $sql = "SELECT COUNT(*) FROM teachers";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération du nombre total d'enseignants : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    /**
     * Récupère le nombre total de classes.
     *
     * @return int Le nombre total de classes.
     */
    public function getTotalClasses(): int
    {
        $sql = "SELECT COUNT(*) FROM classes";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération du nombre total de classes : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    /**
     * Récupère le nombre total de matières.
     *
     * @return int Le nombre total de matières.
     */
    public function getTotalSubjects(): int
    {
        $sql = "SELECT COUNT(*) FROM subjects";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération du nombre total de matières : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    /**
     * Récupère la moyenne générale des notes de tous les étudiants.
     *
     * @return float La moyenne générale ou 0.0 si aucune note.
     */
    public function getOverallAverageGrade(): float
    {
        $sql = "SELECT AVG(grade_value) FROM grades";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return (float)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de la moyenne générale des notes : " . $e->getMessage(), "ERROR");
            return 0.0;
        }
    }

    public function getTotalAbsences(): int
    {
        $sql = "SELECT COUNT(*) FROM absences";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    public function getTotalUnjustifiedAbsences(): int
    {
        $sql = "SELECT COUNT(*) FROM absences WHERE justified = FALSE";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    public function getAllTeacherAssignments(): array
    {
        $sql = "SELECT tsc.id, c.name as class_name, sub.name as subject_name, u.first_name, u.last_name, u.email
                FROM teacher_subject_class tsc
                JOIN teachers t ON tsc.teacher_id = t.id
                JOIN users u ON t.user_id = u.id
                JOIN subjects sub ON tsc.subject_id = sub.id
                JOIN classes c ON tsc.class_id = c.id
                ORDER BY c.name, sub.name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    public function getAllGrades(): array
    {
        $sql = "SELECT g.id, g.grade_value, g.grade_date, g.comment,
                       u_st.first_name as student_first_name, u_st.last_name as student_last_name,
                       sub.name as subject_name, c.name as class_name,
                       u_t.first_name as teacher_first_name, u_t.last_name as teacher_last_name
                FROM grades g
                JOIN students st ON g.student_id = st.id
                JOIN users u_st ON st.user_id = u_st.id
                LEFT JOIN classes c ON st.class_id = c.id
                JOIN subjects sub ON g.subject_id = sub.id
                JOIN teachers t ON g.teacher_id = t.id
                JOIN users u_t ON t.user_id = u_t.id
                ORDER BY g.grade_date DESC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    public function getAllAbsences(): array
    {
        $sql = "SELECT a.id, a.absence_date, a.justified, a.justification_details,
                       u.first_name, u.last_name,
                       sub.name as subject_name, c.name as class_name
                FROM absences a
                JOIN students st ON a.student_id = st.id
                JOIN users u ON st.user_id = u.id
                LEFT JOIN subjects sub ON a.subject_id = sub.id
                LEFT JOIN classes c ON st.class_id = c.id
                ORDER BY a.absence_date DESC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur : " . $e->getMessage(), "ERROR");
            return [];
        }
    }
}
