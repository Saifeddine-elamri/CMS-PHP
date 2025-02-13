<?php
namespace App\Middleware;

use App\Core\Session;

class AuthMiddleware {

    public static function handle() {
        // Démarre la session pour vérifier si l'utilisateur est authentifié
        Session::start();

        // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
        if (!Session::isAuthenticated()) {
            // Sauvegarder l'URL de la page demandée dans la session avant la redirection
            Session::set('redirect_url', $_SERVER['REQUEST_URI']);
            header('Location: /login');
            exit();
        }
    }
}


