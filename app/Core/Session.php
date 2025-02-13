<?php
namespace App\Core;

class Session {

    // Démarre la session si elle n'est pas déjà démarrée
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Détruire la session pour déconnecter l'utilisateur
    public static function logout() {
        self::start(); // Démarrer la session si ce n'est pas déjà fait
        
        // Supprimer toutes les variables de session
        session_unset();
        
        // Détruire la session
        session_destroy();
    
        // Vérifier si la session a été détruite avec succès
        if (session_status() == PHP_SESSION_NONE) {
            // Si la session est bien fermée, procéder à la redirection
            header('Location: /login'); // Rediriger vers la page de connexion
            exit(); // Ne pas oublier de mettre exit() après header pour arrêter le script
        } else {
            // En cas d'échec de la destruction de la session, gérer l'erreur
            // Tu peux ajouter une gestion d'erreur ici si nécessaire, par exemple :
            echo "Erreur : la session n'a pas pu être fermée.";
            // Ou rediriger vers une autre page si besoin
        }
    }

    // Vérifie si un utilisateur est authentifié (session active)
    public static function isAuthenticated() {
        self::start(); // S'assurer que la session est démarrée
        return isset($_SESSION['user']);
    }

    // Récupérer une valeur dans la session
    public static function get($key) {
        self::start(); // S'assurer que la session est démarrée
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null; // Retourner la valeur ou null si la clé n'existe pas
    }

    // Définir une valeur dans la session
    public static function set($key, $value) {
        self::start(); // S'assurer que la session est démarrée
        $_SESSION[$key] = $value; // Définir la valeur dans la session
    }

    // Supprimer une clé spécifique de la session
    public static function unset($key) {
        self::start(); // S'assurer que la session est démarrée
        unset($_SESSION[$key]); // Supprimer la clé spécifique de la session
    }
}
