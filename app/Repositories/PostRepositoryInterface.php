<?php
namespace App\Repositories;

interface PostRepositoryInterface {
    // Récupère tous les posts
    public function findAll();

    // Récupère un post par son ID
    public function findById($id);

    // Insère un nouveau post (avec image ou PDF)
    public function create(array $data);

    // Met à jour un post existant
    public function update($id, array $data);

    // Supprime un post par son ID
    public function delete($id);

    // Récupère une liste de posts paginés
    public function findPaginated($offset, $limit);

    // Récupère le nombre total de posts
    public function getTotalPosts();
}
