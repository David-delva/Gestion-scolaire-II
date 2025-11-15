<?php
//
// Fichier : app/Models/GradeModel.php
// Description : Modèle pour la gestion des notes.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Models;

use PDO;
use PDOException;

class GradeModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupère toutes les notes.
     *
     * @return array Un tableau de toutes les notes.
     */
    public function getAllGrades(): array
    {
        $sql = "SELECT g.id, g.grade_value, g.grade_date, g.comment,\n                       st.id as student_id, u_st.first_name as student_first_name, u_st.last_name as student_last_name,\n                       sub.id as subject_id, sub.name as subject_name,\n                       t.id as teacher_id, u_t.first_name as teacher_first_name, u_t.last_name as teacher_last_name\n                FROM grades g\n                JOIN students st ON g.student_id = st.id\n                JOIN users u_st ON st.user_id = u_st.id\n                JOIN subjects sub ON g.subject_id = sub.id\n                JOIN teachers t ON g.teacher_id = t.id\n                JOIN users u_t ON t.user_id = u_t.id\n                ORDER BY g.grade_date DESC, u_st.last_name, sub.name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de toutes les notes : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Récupère les notes pour un enseignant spécifique.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @return array Un tableau des notes de l'enseignant.
     */
    public function getGradesByTeacherId(int $teacherId): array
    {
        $sql = "SELECT g.id, g.grade_value, g.category, g.grade_date, g.comment,
                       st.id as student_id, u_st.first_name as student_first_name, u_st.last_name as student_last_name,
                       sub.id as subject_id, sub.name as subject_name,
                       t.id as teacher_id, u_t.first_name as teacher_first_name, u_t.last_name as teacher_last_name,
                       c.name as class_name
                FROM grades g
                JOIN students st ON g.student_id = st.id
                JOIN users u_st ON st.user_id = u_st.id
                JOIN subjects sub ON g.subject_id = sub.id
                JOIN teachers t ON g.teacher_id = t.id
                JOIN users u_t ON t.user_id = u_t.id
                LEFT JOIN classes c ON st.class_id = c.id
                WHERE g.teacher_id = :teacher_id
                ORDER BY g.grade_date DESC, u_st.last_name, sub.name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des notes par enseignant : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Récupère les notes pour un étudiant spécifique.
     *
     * @param int $studentId L'ID de l'étudiant.
     * @return array Un tableau des notes de l'étudiant.
     */
    public function getGradesByStudentId(int $studentId): array
    {
        $sql = "SELECT g.id, g.grade_value, g.category, g.grade_date, g.comment,\n                       sub.name as subject_name,\n                       u_t.first_name as teacher_first_name, u_t.last_name as teacher_last_name\n                FROM grades g\n                JOIN subjects sub ON g.subject_id = sub.id\n                JOIN teachers t ON g.teacher_id = t.id\n                JOIN users u_t ON t.user_id = u_t.id\n                WHERE g.student_id = :student_id\n                ORDER BY sub.name, g.category, g.grade_date DESC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['student_id' => $studentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des notes par étudiant : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    

    /**
     * Récupère une note par son ID.
     *
     * @param int $id L'ID de la note.
     * @return array|false Les données de la note ou false si non trouvée.
     */
    public function getGradeById(int $id)
    {
        $sql = "SELECT * FROM grades WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de la note par ID : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Ajoute une nouvelle note.
     *
     * @param int $studentId L'ID de l'étudiant.
     * @param int $subjectId L'ID de la matière.
     * @param int $teacherId L'ID de l'enseignant qui donne la note.
     * @param float $gradeValue La valeur de la note.
     * @param string $gradeDate La date de la note.
     * @param string|null $comment Un commentaire facultatif.
     * @return int|false L'ID de la nouvelle note ou false en cas d'échec.
     */
    public function addGrade(int $studentId, int $subjectId, int $teacherId, float $gradeValue, string $gradeDate, ?string $comment = null, ?string $category = 'Devoir')
    {
        $sql = "INSERT INTO grades (student_id, subject_id, teacher_id, grade_value, category, grade_date, comment) VALUES (:student_id, :subject_id, :teacher_id, :grade_value, :category, :grade_date, :comment)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'teacher_id' => $teacherId,
                'grade_value' => $gradeValue,
                'category' => $category,
                'grade_date' => $gradeDate,
                'comment' => $comment
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            log_message("Erreur lors de l'ajout de la note : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Met à jour une note existante.
     *
     * @param int $id L'ID de la note à mettre à jour.
     * @param int $studentId L'ID de l'étudiant.
     * @param int $subjectId L'ID de la matière.
     * @param float $gradeValue La nouvelle valeur de la note.
     * @param string $gradeDate La nouvelle date de la note.
     * @param string|null $comment Le nouveau commentaire facultatif.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function updateGrade(int $id, int $studentId, int $subjectId, float $gradeValue, string $gradeDate, ?string $comment = null, ?string $category = 'Devoir'): bool
    {
        $sql = "UPDATE grades SET student_id = :student_id, subject_id = :subject_id, grade_value = :grade_value, category = :category, grade_date = :grade_date, comment = :comment WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'grade_value' => $gradeValue,
                'category' => $category,
                'grade_date' => $gradeDate,
                'comment' => $comment,
                'id' => $id
            ]);
        } catch (PDOException $e) {
            log_message("Erreur lors de la mise à jour de la note : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Supprime une note par son ID.
     *
     * @param int $id L'ID de la note à supprimer.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function deleteGrade(int $id): bool
    {
        $sql = "DELETE FROM grades WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            log_message("Erreur lors de la suppression de la note : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Récupère le nombre total de notes données par un enseignant.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @return int Le nombre total de notes.
     */
    public function getTotalGradesByTeacher(int $teacherId): int
    {
        $sql = "SELECT COUNT(*) FROM grades WHERE teacher_id = :teacher_id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération du nombre total de notes par enseignant : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    /**
     * Récupère la moyenne des notes données par un enseignant.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @return float La moyenne des notes ou 0.0 si aucune note.
     */
    public function getAverageGradeByTeacher(int $teacherId): float
    {
        $sql = "SELECT AVG(grade_value) FROM grades WHERE teacher_id = :teacher_id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            return (float)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de la moyenne des notes par enseignant : " . $e->getMessage(), "ERROR");
            return 0.0;
        }
    }

    /**
     * Récupère les meilleurs étudiants par note pour un enseignant.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @param int $limit Le nombre de résultats à retourner.
     * @return array Un tableau des meilleurs étudiants par note.
     */
    public function getTopStudentsByGradeForTeacher(int $teacherId, int $limit = 5): array
    {
        $sql = "SELECT u.first_name, u.last_name, AVG(g.grade_value) as average_grade\n                FROM grades g\n                JOIN students st ON g.student_id = st.id\n                JOIN users u ON st.user_id = u.id\n                WHERE g.teacher_id = :teacher_id\n                GROUP BY st.id\n                ORDER BY average_grade DESC\n                LIMIT :limit";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':teacher_id', $teacherId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des meilleurs étudiants par note pour l'enseignant : " . $e->getMessage(), "ERROR");
            return [];
        }
    }
}