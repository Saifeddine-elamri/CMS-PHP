<?php
namespace App\Repositories;

use App\Core\Database;

class ArticleRepository {
    protected $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Récupère tous les articles
    public function findAll() {
        $stmt = $this->db->query('SELECT * FROM articles');
        return $stmt->fetchAll();
    }

    // Récupère un article par son ID
    public function findById($id) {
        $stmt = $this->db->prepare('SELECT * FROM articles WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Crée un nouvel article
    public function create(array $data) {
        $stmt = $this->db->prepare('INSERT INTO articles (title, content, author_id) VALUES (?, ?, ?)');
        $stmt->execute([$data['title'], $data['content'], $data['author_id']]);
        return $this->db->lastInsertId();
    }
}