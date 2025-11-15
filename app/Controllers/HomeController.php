<?php
//
// Fichier : app/Controllers/HomeController.php
// Description : Contrôleur par défaut pour la page d'accueil.
// Version : 1.0
// Date : Octobre 2025
//

namespace App\Controllers;

class HomeController extends BaseController
{
    /**
     * Affiche la page d'accueil de l'application.
     */
    public function index()
    {
        // Les données à passer à la vue (si nécessaire)
        $data = [
            'title' => 'Accueil - Gestion des Étudiants',
            'welcome_message' => 'Bienvenue sur le système de Gestion des Étudiants !',
        ];

        $this->render('home/index', $data);
    }
}
