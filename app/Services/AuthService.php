<?php
namespace App\Services;

use App\Repositories\UserRepository;
use App\Core\Session;

class AuthService {

    protected $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    // Méthode pour connecter un utilisateur
    public function login($username, $password) {
        // Cherche l'utilisateur dans le repository
        $user = $this->userRepository->findByUsername($username);

        // Si l'utilisateur existe et que le mot de passe est valide
        if ($user && password_verify($password, $user['password'])) {
            // Démarre la session si l'utilisateur est authentifié via la classe Session
            Session::start();
            Session::set('user', $user['id']); // L'ID de l'utilisateur est stocké dans la session
            Session::set('username', $user['username']); // Vous pouvez aussi stocker d'autres informations
            Session::set('user_role', $user['role']);  // Store the role in the session
            return true;
        }

        // Si l'utilisateur n'existe pas ou si le mot de passe est incorrect
        return false;
    }

    // Méthode pour déconnecter un utilisateur
    public function logout() {
        // Déconnecte l'utilisateur en appelant la méthode logout de la classe Session
        Session::logout();
    }

    // Méthode pour vérifier si un utilisateur est connecté
    public function isAuthenticated() {
        // Vérifie si l'utilisateur est connecté via la méthode isAuthenticated de la classe Session
        return Session::isAuthenticated();
    }

    // Récupérer l'utilisateur connecté (si connecté)
    public function getUser() {
        if ($this->isAuthenticated()) {
            return $this->userRepository->findById(Session::get('user'));
        }
        return null; // Aucun utilisateur connecté
    }
}
