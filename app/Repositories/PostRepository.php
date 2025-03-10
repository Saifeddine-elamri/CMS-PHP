<?php
namespace App\Repositories;

use App\Core\Database;

class PostRepository implements PostRepositoryInterface {
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
            // Commencer la requête SQL
            $sql = 'UPDATE posts SET title = ?, content = ?';
            $params = [
                $data['title'],
                $data['content']
            ];

            // Si file_path est présent dans les données, on l'ajoute à la requête
            if (isset($data['file_path'])) {
                $sql .= ', file_path = ?';
                $params[] = $data['file_path'];
            }

            // Continuer avec les autres champs
            $sql .= ', author_id = ?, created_at = ? WHERE id = ?';
            $params[] = $data['author_id'];
            $params[] = $data['created_at'];
            $params[] = $id;

            // Préparer et exécuter la requête
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return $stmt->rowCount();
        }


    // Supprime un post par son ID
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM posts WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }


    public function findPaginated($offset, $limit) {
        // Sécuriser les variables en forçant à des entiers
        $limit = (int) $limit;
        $offset = (int) $offset;
    
        // Effectuer une requête pour récupérer les posts en utilisant OFFSET et LIMIT
        $sql = "SELECT * FROM posts ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
        
        // Exécuter la requête
        $stmt = $this->db->query($sql);
        
        return $stmt->fetchAll();
    }
    
    public function getTotalPosts() {
        // Effectuer une requête pour obtenir le nombre total de posts
        $sql = "SELECT COUNT(*) AS total FROM posts";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        // Récupérer le nombre total de posts
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    
}