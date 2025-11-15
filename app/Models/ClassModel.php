<?php
//
// Fichier : app/Models/ClassModel.php
// Description : Modèle pour la gestion des classes.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Models;

use PDO;
use PDOException;

class ClassModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupère toutes les classes.
     *
     * @return array Un tableau de toutes les classes.
     */
    public function getAllClasses(): array
    {
        $sql = "SELECT * FROM classes ORDER BY name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de toutes les classes : " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    /**
     * Récupère une classe par son ID.
     *
     * @param int $id L'ID de la classe.
     * @return array|false Les données de la classe ou false si non trouvée.
     */
    public function getClassById(int $id)
    {
        $sql = "SELECT * FROM classes WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_message("Erreur lors de la récupération de la classe par ID : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Ajoute une nouvelle classe.
     *
     * @param string $name Le nom de la classe.
     * @return int|false L'ID de la nouvelle classe ou false en cas d'échec.
     */
    public function addClass(string $name)
    {
        $sql = "INSERT INTO classes (name) VALUES (:name)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['name' => $name]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            log_message("Erreur lors de l'ajout de la classe : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Met à jour une classe existante.
     *
     * @param int $id L'ID de la classe à mettre à jour.
     * @param string $name Le nouveau nom de la classe.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function updateClass(int $id, string $name): bool
    {
        $sql = "UPDATE classes SET name = :name WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'name' => $name,
                'id' => $id
            ]);
        } catch (PDOException $e) {
            log_message("Erreur lors de la mise à jour de la classe : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Supprime une classe par son ID.
     *
     * @param int $id L'ID de la classe à supprimer.
     * @return bool True en cas de succès, false en cas d'échec.
     */
    public function deleteClass(int $id): bool
    {
        $sql = "DELETE FROM classes WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            log_message("Erreur lors de la suppression de la classe : " . $e->getMessage(), "ERROR");
            return false;
        }
    }

    /**
     * Vérifie si une classe existe par son nom (pour éviter les doublons).
     *
     * @param string $name Le nom de la classe.
     * @param int|null $excludeId L'ID de la classe à exclure de la vérification (pour les mises à jour).
     * @return array|false Les données de la classe si elle existe, sinon false.
     */
    public function getClassByName(string $name, ?int $excludeId = null)
    {
        $sql = "SELECT * FROM classes WHERE name = :name";
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
            log_message("Erreur lors de la vérification de l'existence de la classe par nom : " . $e->getMessage(), "ERROR");
            return false;
        }
    }
}
