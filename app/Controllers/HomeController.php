<?php
namespace App\Controllers;

use App\Repositories\PostRepository;
use App\Core\View;
use App\Middleware\AuthMiddleware;  // Ajout du middleware

class HomeController {

    protected $postRepository;

    public function __construct() {
        $this->postRepository = new PostRepository();
    }

    // Méthode pour afficher tous les posts
    public function index() {
        // Vérifie si l'utilisateur est authentifié avant d'afficher les posts

        $posts = $this->postRepository->findAll();  // Récupérer tous les posts
        View::render('post/index', ['posts' => $posts]);  // Afficher la vue avec les posts
    }

    // Nouvelle méthode list() pour lister tous les posts
    public function list() {
        // Vérifie si l'utilisateur est authentifié avant d'afficher la liste des posts

        $posts = $this->postRepository->findAll();  // Récupérer tous les posts
        View::render('post/list', ['posts' => $posts]);  // Afficher la vue avec la liste des posts
    }

    // Méthode pour afficher un post spécifique
    public function show($id) {
        // Vérifie si l'utilisateur est authentifié avant d'afficher un post spécifique

        $post = $this->postRepository->findById($id);  // Récupérer un post par son ID
        if ($post) {
            View::render('post/show', ['post' => $post]);  // Afficher la vue avec un post spécifique
        } else {
            echo 'Article non trouvé';
        }
    }

 // Méthode pour créer un post avec upload d'image ou PDF
public function create() {
    // Vérifie si l'utilisateur est authentifié avant de permettre la création
    // (Ajoutez ici votre logique d'authentification si nécessaire)

    // Si la requête est en POST, on traite le formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Contrôler que les champs sont remplis et valides
        if (empty($_POST['title']) || empty($_POST['content']) || empty($_POST['author_id'])) {
            echo "Tous les champs sont obligatoires.";
            return;
        }

        // Sécurisation des données reçues
        $title = htmlspecialchars($_POST['title']);
        $content = htmlspecialchars($_POST['content']);
        $author_id = (int)$_POST['author_id'];
        $created_at = date('Y-m-d H:i:s');
        $filePath = null; // Initialiser le chemin du fichier

        // Vérification de l'upload de l'image ou du PDF
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $fileName = uniqid() . '-' . basename($file['name']);
            $uploadDir = 'public/uploads/';
            $uploadFile = $uploadDir . $fileName;

            // Vérification du type de fichier (image ou PDF)
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
            if (in_array($file['type'], $allowedTypes)) {
                // Déplacement du fichier vers le répertoire de destination
                if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                    $filePath = $uploadFile;
                } else {
                    echo "Erreur lors du téléchargement du fichier.";
                    return;
                }
            } else {
                echo "Format de fichier non supporté. Seuls les fichiers image ou PDF sont autorisés.";
                return;
            }
        }

        // Préparation des données à envoyer au modèle
        $data = [
            'title' => $title,
            'content' => $content,
            'author_id' => $author_id,
            'created_at' => $created_at,
            'file_path' => $filePath // Ajout du chemin du fichier (image ou PDF)
        ];

        // Créer le post dans la base de données
        $postId = $this->postRepository->create($data);  
        echo 'Post créé avec succès, ID : ' . $postId;

        // Redirection après création
        header('Location: /post/list');
        exit;
    } else {
        // Sinon, afficher le formulaire de création d'un post
        View::render('post/create');
    }
}



    // Méthode pour mettre à jour un post
    public function update($id) {
        // Vérifie si l'utilisateur est authentifié avant de permettre la mise à jour

        // Récupérer les données envoyées via un formulaire POST
        $data = [
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'author_id' => $_POST['author_id'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        $affectedRows = $this->postRepository->update($id, $data);  // Mettre à jour le post dans la base de données
        echo $affectedRows . ' ligne(s) mise(s) à jour';  // Afficher le nombre de lignes affectées
    }

    // Méthode pour supprimer un post
    public function delete($id) {
        // Vérifie si l'utilisateur est authentifié avant de permettre la suppression

        $affectedRows = $this->postRepository->delete($id);  // Supprimer le post dans la base de données
        echo $affectedRows . ' ligne(s) supprimée(s)';  // Afficher le nombre de lignes supprimées
    }
}
