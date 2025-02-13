<?php
namespace App\Repositories;

use App\Core\Database;

class PostRepository {
    protected $db;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Récupère tous les posts
    public function findAll() {
        $stmt = $this->db->query('SELECT * FROM posts');
        return $stmt->fetchAll();
    }

    // Récupère un post par son ID
    public function findById($id) {
        $stmt = $this->db->prepare('SELECT * FROM posts WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

// Méthode pour insérer un post dans la base de données avec image ou PDF
        public function create(array $data) {
            // On prépare la requête SQL d'insertion
            $stmt = $this->db->prepare('
                INSERT INTO posts (title, content, author_id, created_at, file_path) 
                VALUES (?, ?, ?, ?, ?)
            ');

            // Exécution de la requête avec les données du post (titre, contenu, auteur, date de création et fichier)
            $stmt->execute([
                $data['title'],
                $data['content'],
                $data['author_id'],
                $data['created_at'],
                $data['file_path'] // Enregistrement du chemin du fichier (image ou PDF)
            ]);

            // Retourne l'ID du dernier post inséré
            return $this->db->lastInsertId();
        }



    // Met à jour un post existant
    public function update($id, array $data) {
        $stmt = $this->db->prepare('UPDATE posts SET title = ?, content = ?, author_id = ?, created_at = ? WHERE id = ?');
        $stmt->execute([
            $data['title'],
            $data['content'],
            $data['author_id'],
            $data['created_at'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Supprime un post par son ID
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM posts WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
