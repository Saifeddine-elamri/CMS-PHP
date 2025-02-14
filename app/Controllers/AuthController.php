<?php
namespace App\Controllers;

use App\Models\User;
use App\Core\View;
use App\Services\AuthService;
use App\Repositories\UserRepository;
use App\Core\Session;
use App\Core\Http;

class AuthController {

    protected $authService;
    protected $userRepository;

    public function __construct() {
        $this->authService = new AuthService();
        $this->userRepository = new UserRepository();
    }

    // Méthode pour afficher le formulaire de connexion
    public function loginForm() {
        View::render('auth/login');
    }

    // Méthode pour traiter la connexion de l'utilisateur
    #[Http('POST')]
    public function login() {
        $errorMessage = ''; // Variable pour le message d'erreur

            $username = $_POST['username'];
            $password = $_POST['password'];

            // Authentifier l'utilisateur
            if ($this->authService->login($username, $password)) {
                // Vérifie si une URL de redirection est définie
                $redirectUrl = Session::get('redirect_url');
                
                // Si une redirection est définie, rediriger l'utilisateur vers cette page
                if ($redirectUrl) {
                    Session::unset('redirect_url'); // Supprimer l'URL de redirection de la session
                    header("Location: $redirectUrl"); // Rediriger vers la page initiale demandée
                } else {
                    header('Location: /'); // Rediriger vers le tableau de bord par défaut
                }
                exit;
            } else {
                // Si l'authentification échoue, définir le message d'erreur
                $errorMessage = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        

        // Passer le message d'erreur à la vue
        View::render('auth/login', ['errorMessage' => $errorMessage]);
    }

    // Méthode pour déconnecter l'utilisateur
    public function logout() {
        $this->authService->logout();
    }

    // Méthode pour afficher le formulaire d'inscription
    public function registerForm() {
        View::render('auth/register');
    }

    // Méthode pour traiter l'inscription de l'utilisateur
    #[Http('POST')]
    public function register() {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Vérifier que le mot de passe et la confirmation du mot de passe correspondent
            if ($password !== $confirmPassword) {
                echo "Les mots de passe ne correspondent pas.";
                return;
            }

            // Vérifier si l'utilisateur existe déjà
            if ($this->userRepository->findByUsername($username)) {
                echo "Nom d'utilisateur déjà pris.";
                return;
            }

            // Créer un nouvel utilisateur
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hachage du mot de passe
            $userData = [
                'username' => $username,
                'password' => $hashedPassword
            ];

            // Sauvegarder le nouvel utilisateur en base de données
            $this->userRepository->create($userData);

            // Authentifier l'utilisateur après l'enregistrement
            $this->authService->login($username, $password);

            // Rediriger l'utilisateur vers son tableau de bord après l'enregistrement
            header('Location: /');
        
    }
}
