<?php
namespace App\Repositories;

use App\Core\Database;

class UserRepository {

    protected $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Trouver un utilisateur par son nom d'utilisateur
    public function findByUsername($username) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch(); // Retourne les informations de l'utilisateur ou false
    }

    // Trouver un utilisateur par son ID
    public function findById($id) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(); // Retourne les informations de l'utilisateur ou false
    }



        // Créer un nouvel utilisateur
        public function create($userData) {
            // Préparer la requête d'insertion avec les données de l'utilisateur
            $stmt = $this->db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
            $stmt->execute([$userData['username'], $userData['password']]);

            // Retourner l'ID du nouvel utilisateur
            return $this->db->lastInsertId();
        }

                // Exemple de méthode dans UserRepository pour obtenir les administrateurs
        public function getAdmins() {
            return $this->db->query("SELECT id, username FROM users WHERE role = 'admin'")->fetchAll();
        }

}
