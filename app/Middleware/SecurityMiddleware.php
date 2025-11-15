<?php
//
// Fichier : app/Middleware/SecurityMiddleware.php
// Description : Middleware de sécurité pour l'application
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Middleware;

class SecurityMiddleware
{
    /**
     * Applique les en-têtes de sécurité
     */
    public static function applySecurityHeaders()
    {
        // Protection contre le clickjacking
        header('X-Frame-Options: DENY');
        
        // Protection contre le sniffing de type MIME
        header('X-Content-Type-Options: nosniff');
        
        // Protection XSS
        header('X-XSS-Protection: 1; mode=block');
        
        // Politique de sécurité du contenu (CSP) basique
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://code.jquery.com https://cdn.datatables.net https://unpkg.com; style-src 'self' 'unsafe-inline' https://bootswatch.com https://cdn.jsdelivr.net https://cdn.datatables.net https://fonts.googleapis.com https://cdnjs.cloudflare.com https://unpkg.com; font-src 'self' https://cdn.jsdelivr.net https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://bootswatch.com https://cdn.jsdelivr.net;");
        
        // Protection contre les attaques de référent
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }

    /**
     * Limite le taux de requêtes (protection basique contre le brute force)
     */
    public static function rateLimiting($maxAttempts = 5, $timeWindow = 300)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $key = 'rate_limit_' . $ip;
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'time' => time()];
        }
        
        $data = $_SESSION[$key];
        
        // Reset si la fenêtre de temps est dépassée
        if (time() - $data['time'] > $timeWindow) {
            $_SESSION[$key] = ['count' => 1, 'time' => time()];
            return true;
        }
        
        // Vérifier si la limite est atteinte
        if ($data['count'] >= $maxAttempts) {
            http_response_code(429);
            die('Trop de tentatives. Veuillez réessayer plus tard.');
        }
        
        $_SESSION[$key]['count']++;
        return true;
    }
}