<?php
//
// Fichier : app/Constants/Messages.php
// Description : Constantes pour les messages d'erreur et de succès
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Constants;

class Messages
{
    // Messages d'erreur génériques
    const ERROR_GENERIC = 'Une erreur est survenue. Veuillez réessayer.';
    const ERROR_UNAUTHORIZED = 'Vous n\'avez pas les permissions nécessaires.';
    const ERROR_NOT_FOUND = 'Élément non trouvé.';
    const ERROR_INVALID_DATA = 'Données invalides.';
    const ERROR_CSRF = 'Token de sécurité invalide.';
    
    // Messages d'authentification
    const AUTH_LOGIN_SUCCESS = 'Connexion réussie. Bienvenue !';
    const AUTH_LOGIN_FAILED = 'Email ou mot de passe incorrect.';
    const AUTH_LOGOUT_SUCCESS = 'Vous avez été déconnecté.';
    const AUTH_LOGIN_REQUIRED = 'Veuillez vous connecter pour accéder à cette page.';
    
    // Messages de validation
    const VALIDATION_REQUIRED_FIELDS = 'Tous les champs obligatoires doivent être remplis.';
    const VALIDATION_INVALID_EMAIL = 'L\'adresse email n\'est pas valide.';
    const VALIDATION_PASSWORD_LENGTH = 'Le mot de passe doit contenir au moins 8 caractères.';
    const VALIDATION_PASSWORDS_MISMATCH = 'Les mots de passe ne correspondent pas.';
    
    // Messages de succès
    const SUCCESS_CREATED = 'Élément créé avec succès.';
    const SUCCESS_UPDATED = 'Élément mis à jour avec succès.';
    const SUCCESS_DELETED = 'Élément supprimé avec succès.';
    const SUCCESS_PROFILE_UPDATED = 'Profil mis à jour avec succès.';
}