<?php
//
// Fichier : app/Models/AbsenceModel.php
// Description : Modèle pour la gestion des absences.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Models;

use PDO;
use PDOException;

class AbsenceModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupère toutes les absences.
     *
     * @return array Un tableau de toutes les absences.
     */
    public function getAllAbsences(): array
    {
        $sql = "SELECT a.id, a.absence_date, a.justified, a.justification_details,
                       st.id as student_id, u_st.first_name as student_first_name, u_st.last_name as student_last_name,
                       sub.id as subject_id, sub.name as subject_name,
                       c.id as class_id, c.name as class_name
                FROM absences a
                JOIN students st ON a.student_id = st.id
                JOIN users u_st ON st.user_id = u_st.id
                LEFT JOIN subjects sub ON a.subject_id = sub.id
                LEFT JOIN classes c ON a.class_id = c.id
                ORDER BY a.absence_date DESC, u_st.last_name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de toutes les absences : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Récupère les absences pour un enseignant spécifique (celles de ses classes/matières).
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @return array Un tableau des absences gérées par l'enseignant.
     */
    public function getAbsencesByTeacherId(int $teacherId): array
    {
        $sql = "SELECT a.id, a.absence_date, a.justified, a.justification_details,
                       st.id as student_id, u_st.first_name as student_first_name, u_st.last_name as student_last_name,
                       sub.id as subject_id, sub.name as subject_name,
                       c.id as class_id, c.name as class_name
                FROM absences a
                JOIN students st ON a.student_id = st.id
                JOIN users u_st ON st.user_id = u_st.id
                LEFT JOIN subjects sub ON a.subject_id = sub.id
                LEFT JOIN classes c ON st.class_id = c.id
                WHERE st.class_id IN (
                    SELECT DISTINCT tsc.class_id
                    FROM teacher_subject_class tsc
                    WHERE tsc.teacher_id = :teacher_id
                )
                ORDER BY a.absence_date DESC, u_st.last_name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des absences par enseignant : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Récupère les absences pour un étudiant spécifique.
     *
     * @param int $studentId L'ID de l'étudiant.
     * @return array Un tableau des absences de l'étudiant.
     */
    public function getAbsencesByStudentId(int $studentId): array
    {
        $sql = "SELECT a.id, a.absence_date, a.justified, a.justification_details,
                       sub.name as subject_name,
                       c.name as class_name
                FROM absences a
                LEFT JOIN subjects sub ON a.subject_id = sub.id
                LEFT JOIN classes c ON a.class_id = c.id
                WHERE a.student_id = :student_id
                ORDER BY a.absence_date DESC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['student_id' => $studentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des absences par étudiant : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Récupère le nombre total d'absences pour un étudiant spécifique.
     *
     * @param int $studentId L'ID de l'étudiant.
     * @return int Le nombre total d'absences.
     */
    public function getTotalAbsencesByStudentId(int $studentId): int
    {
        $sql = "SELECT COUNT(*) FROM absences WHERE student_id = :student_id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['student_id' => $studentId]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération du nombre total d'absences par étudiant : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    /**
     * Récupère le nombre total d'absences justifiées pour un étudiant spécifique.
     *
     * @param int $studentId L'ID de l'étudiant.
     * @return int Le nombre total d'absences justifiées.
     */
    public function getTotalJustifiedAbsencesByStudentId(int $studentId): int
    {
        $sql = "SELECT COUNT(*) FROM absences WHERE student_id = :student_id AND justified = TRUE";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['student_id' => $studentId]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération du nombre total d'absences justifiées par étudiant : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    /**
     * Récupère le nombre total d'absences non justifiées pour un étudiant spécifique.
     *
     * @param int $studentId L'ID de l'étudiant.
     * @return int Le nombre total d'absences non justifiées.
     */
    public function getTotalUnjustifiedAbsencesByStudentId(int $studentId): int
    {
        $sql = "SELECT COUNT(*) FROM absences WHERE student_id = :student_id AND justified = FALSE";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['student_id' => $studentId]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération du nombre total d'absences non justifiées par étudiant : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    /**
     * Récupère une absence par son ID.
     *
     * @param int $id L'ID de l'absence.
     * @return array|false Les données de l'absence ou false si non trouvée.
     */
    public function getAbsenceById(int $id)
    {
        $sql = "SELECT * FROM absences WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de l'absence par ID : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Ajoute une nouvelle absence.
     *
     * @param int $studentId L'ID de l'étudiant.
     * @param int|null $subjectId L'ID de la matière (peut être null).
     * @param int|null $classId L'ID de la classe (peut être null).
     * @param string $absenceDate La date de l'absence.
     * @param bool $justified Si l'absence est justifiée.
     * @param string|null $justificationDetails Détails de la justification.
     * @return int|false L'ID de la nouvelle absence ou false en cas d'échec.
     */
    public function addAbsence(int $studentId, ?int $subjectId, ?int $classId, string $absenceDate, bool $justified, ?string $justificationDetails = null)
    {
        $sql = "INSERT INTO absences (student_id, subject_id, class_id, absence_date, justified, justification_details) VALUES (:student_id, :subject_id, :class_id, :absence_date, :justified, :justification_details)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'class_id' => $classId,
                'absence_date' => $absenceDate,
                'justified' => $justified,
                'justification_details' => $justificationDetails
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            log_message("Erreur lors de l'ajout de l'absence : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Met à jour une absence existante.
     *
     * @param int $id L'ID de l'absence à mettre à jour.
     * @param int $studentId L'ID de l'étudiant.
     * @param int|null $subjectId L'ID de la matière (peut être null).
     * @param int|null $classId L'ID de la classe (peut être null).
     * @param string $absenceDate La nouvelle date de l'absence.
     * @param bool $justified Si l'absence est justifiée.
     * @param string|null $justificationDetails Nouveaux détails de la justification.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function updateAbsence(int $id, int $studentId, ?int $subjectId, ?int $classId, string $absenceDate, bool $justified, ?string $justificationDetails = null): bool
    {
        $sql = "UPDATE absences SET student_id = :student_id, subject_id = :subject_id, class_id = :class_id, absence_date = :absence_date, justified = :justified, justification_details = :justification_details WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'class_id' => $classId,
                'absence_date' => $absenceDate,
                'justified' => $justified,
                'justification_details' => $justificationDetails,
                'id' => $id
            ]);
        } catch (PDOException $e) {
            log_message("Erreur lors de la mise à jour de l'absence : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Supprime une absence par son ID.
     *
     * @param int $id L'ID de l'absence à supprimer.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function deleteAbsence(int $id): bool
    {
        $sql = "DELETE FROM absences WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            log_message("Erreur lors de la suppression de l'absence : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Récupère le nombre total d'absences pour un enseignant (dans ses classes/matières).
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @return int Le nombre total d'absences.
     */
    public function getTotalAbsencesByTeacher(int $teacherId): int
    {
        $sql = "SELECT COUNT(a.id)
                FROM absences a
                JOIN students st ON a.student_id = st.id
                WHERE st.class_id IN (
                    SELECT DISTINCT tsc.class_id
                    FROM teacher_subject_class tsc
                    WHERE tsc.teacher_id = :teacher_id
                )";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération du nombre total d'absences par enseignant : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    /**
     * Récupère le nombre total d'absences justifiées pour un enseignant.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @return int Le nombre total d'absences justifiées.
     */
    public function getTotalJustifiedAbsencesByTeacher(int $teacherId): int
    {
        $sql = "SELECT COUNT(a.id)
                FROM absences a
                JOIN students st ON a.student_id = st.id
                WHERE st.class_id IN (
                    SELECT DISTINCT tsc.class_id
                    FROM teacher_subject_class tsc
                    WHERE tsc.teacher_id = :teacher_id
                )
                AND a.justified = TRUE";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération du nombre total d'absences justifiées par enseignant : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    /**
     * Récupère le nombre total d'absences non justifiées pour un enseignant.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @return int Le nombre total d'absences non justifiées.
     */
    public function getTotalUnjustifiedAbsencesByTeacher(int $teacherId): int
    {
        $sql = "SELECT COUNT(a.id)
                FROM absences a
                JOIN students st ON a.student_id = st.id
                WHERE st.class_id IN (
                    SELECT DISTINCT tsc.class_id
                    FROM teacher_subject_class tsc
                    WHERE tsc.teacher_id = :teacher_id
                )
                AND a.justified = FALSE";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération du nombre total d'absences non justifiées par enseignant : " . $e->getMessage(), "ERROR");
            return 0;
        }
    }

    /**
     * Récupère les étudiants les plus absents pour un enseignant.
     *
     * @param int $teacherId L'ID de l'enseignant.
     * @param int $limit Le nombre de résultats à retourner.
     * @return array Un tableau des étudiants les plus absents.
     */
    public function getMostAbsentStudentsForTeacher(int $teacherId, int $limit = 5): array
    {
        $sql = "SELECT u.first_name, u.last_name, COUNT(a.id) as total_absences
                FROM absences a
                JOIN students st ON a.student_id = st.id
                JOIN users u ON st.user_id = u.id
                WHERE st.class_id IN (
                    SELECT DISTINCT tsc.class_id
                    FROM teacher_subject_class tsc
                    WHERE tsc.teacher_id = :teacher_id
                )
                AND a.justified = FALSE
                GROUP BY st.id, u.first_name, u.last_name
                ORDER BY total_absences DESC
                LIMIT :limit";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':teacher_id', $teacherId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération des étudiants les plus absents pour l'enseignant : " . $e->getMessage(), "ERROR");
            return [];
        }
    }
}