<?php
require_once 'app/Models/User.php';
require_once 'app/Services/AuthService.php';

class UserController {
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $user = new User();
            if ($user->create($username, $password)) {
                echo json_encode(['message' => 'Inscription réussie']);
            } else {
                echo json_encode(['message' => 'Erreur lors de l\'inscription']);
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = new User();
            $userData = $user->findByUsername($username);

            if ($userData && password_verify($password, $userData['password'])) {
                $jwt = AuthService::generateJWT($userData['id']);
                echo json_encode(['token' => $jwt]);
            } else {
                echo json_encode(['message' => 'Nom d\'utilisateur ou mot de passe incorrect']);
            }
        }
    }
}
