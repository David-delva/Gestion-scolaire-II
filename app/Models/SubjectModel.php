<?php
//
// Fichier : app/Models/SubjectModel.php
// Description : Modèle pour la gestion des matières.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Models;

use PDO;
use PDOException;

class SubjectModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupère toutes les matières.
     *
     * @return array Un tableau de toutes les matières.
     */
    public function getAllSubjects(): array
    {
        $sql = "SELECT * FROM subjects ORDER BY name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de toutes les matières : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Récupère une matière par son ID.
     *
     * @param int $id L'ID de la matière.
     * @return array|false Les données de la matière ou false si non trouvée.
     */
    public function getSubjectById(int $id)
    {
        $sql = "SELECT * FROM subjects WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de la matière par ID : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Ajoute une nouvelle matière.
     *
     * @param string $name Le nom de la matière.
     * @return int|false L'ID de la nouvelle matière ou false en cas d'échec.
     */
    public function addSubject(string $name, float $coefficient = 1.00)
    {
        $sql = "INSERT INTO subjects (name, coefficient) VALUES (:name, :coefficient)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['name' => $name, 'coefficient' => $coefficient]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            log_message("Erreur lors de l'ajout de la matière : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Met à jour une matière existante.
     *
     * @param int $id L'ID de la matière à mettre à jour.
     * @param string $name Le nouveau nom de la matière.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function updateSubject(int $id, string $name, float $coefficient = 1.00): bool
    {
        $sql = "UPDATE subjects SET name = :name, coefficient = :coefficient WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'name' => $name,
                'coefficient' => $coefficient,
                'id' => $id
            ]);
        } catch (PDOException $e) {
            log_message("Erreur lors de la mise à jour de la matière : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Supprime une matière par son ID.
     *
     * @param int $id L'ID de la matière à supprimer.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function deleteSubject(int $id): bool
    {
        $sql = "DELETE FROM subjects WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            log_message("Erreur lors de la suppression de la matière : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Vérifie si une matière existe par son nom (pour éviter les doublons).
     *
     * @param string $name Le nom de la matière.
     * @param int|null $excludeId L'ID de la matière à exclure de la vérification (pour les mises à jour).
     * @return array|false Les données de la matière si elle existe, sinon false.
     */
    public function getSubjectByName(string $name, ?int $excludeId = null)
    {
        $sql = "SELECT * FROM subjects WHERE name = :name";
        $params = ['name' => $name];

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la vérification de l'existence de la matière par nom : " . $e->getMessage(), "ERROR");
            return false;
        }
    }
}