<?php
//
// Fichier : app/Utils/Validator.php
// Description : Classe utilitaire pour la validation des données
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Utils;

class Validator
{
    /**
     * Valide une adresse email
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valide un mot de passe (minimum 8 caractères)
     */
    public static function validatePassword(string $password): bool
    {
        return strlen($password) >= 8;
    }

    /**
     * Valide une note (entre 0 et 20)
     */
    public static function validateGrade(float $grade): bool
    {
        return $grade >= 0 && $grade <= 20;
    }

    /**
     * Valide une date au format Y-m-d
     */
    public static function validateDate(string $date): bool
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Nettoie et valide une chaîne de caractères
     */
    public static function sanitizeString(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valide un numéro de téléphone français
     */
    public static function validatePhone(string $phone): bool
    {
        return preg_match('/^0[1-9](?:[0-9]{8})$/', $phone);
    }

    /**
     * Valide un ID numérique
     */
    public static function validateId($id): bool
    {
        return is_numeric($id) && (int)$id > 0;
    }
}