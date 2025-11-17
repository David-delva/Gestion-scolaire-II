<?php
//
// Fichier : app/Models/Database.php
// Description : Classe de gestion de la connexion à la base de données via PDO.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    // Instance de la connexion PDO
    private static $instance = null;
    private $pdo;

    /**
     * Constructeur privé pour implémenter le pattern Singleton.
     * Établit la connexion à la base de données en utilisant PDO.
     */
    private function __construct()
    {
        // Inclure les paramètres de la base de données
        require_once CONFIG_PATH . '/database.php';

        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, DB_OPTIONS);
            // Enregistrer la connexion réussie
            log_message("Connexion à la base de données réussie.", "INFO");
        } catch (PDOException $e) {
            // Enregistrer l'erreur de connexion et arrêter l'application
            log_message("Erreur de connexion à la base de données : " . $e->getMessage(), "ERROR");
            // En mode développement, afficher l'erreur. En production, rediriger ou afficher une page d'erreur générique.
            if (ini_get('display_errors')) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            } else {
                die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
            }
        }
    }

    /**
     * Retourne l'instance unique de la classe Database (Singleton).
     * Crée une nouvelle instance si elle n'existe pas encore.
     *
     * @return Database L'instance de la classe Database.
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Retourne l'objet PDO pour interagir avec la base de données.
     *
     * @return PDO L'objet PDO.
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Exécute une requête préparée.
     *
     * @param string $sql La requête SQL à exécuter.
     * @param array $params Les paramètres à lier à la requête.
     * @return \PDOStatement L'objet PDOStatement résultant de l'exécution de la requête.
     * @throws PDOException En cas d'erreur lors de l'exécution de la requête.
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            log_message("Requête SQL exécutée : " . $sql . " avec paramètres : " . json_encode($params), "DEBUG");
            return $stmt;
        } catch (PDOException $e) {
            log_message("Erreur lors de l'exécution de la requête SQL : " . $e->getMessage() . " SQL: " . $sql . " Params: " . json_encode($params), "ERROR");
            throw $e; // Propage l'exception pour une gestion ultérieure
        }
    }

    /**
     * Récupère une seule ligne de résultat d'une requête préparée.
     *
     * @param string $sql La requête SQL.
     * @param array $params Les paramètres.
     * @return array|false La ligne de résultat sous forme de tableau associatif, ou false si aucun résultat.
     */
    public function fetchOne(string $sql, array $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les lignes de résultat d'une requête préparée.
     *
     * @param string $sql La requête SQL.
     * @param array $params Les paramètres.
     * @return array Un tableau de toutes les lignes de résultat.
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère la dernière ID insérée.
     *
     * @return string L'ID de la dernière ligne insérée.
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }
}
