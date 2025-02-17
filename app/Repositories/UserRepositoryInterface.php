<?php
namespace App\Repositories;

interface UserRepositoryInterface {

    /**
     * Trouver un utilisateur par son nom d'utilisateur
     *
     * @param string $username
     * @return mixed
     */
    public function findByUsername($username);

    /**
     * Trouver un utilisateur par son ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Créer un nouvel utilisateur
     *
     * @param array $userData
     * @return int L'ID du nouvel utilisateur
     */
    public function create($userData);

    /**
     * Obtenir les administrateurs
     *
     * @return array
     */
    public function getAdmins();
}
