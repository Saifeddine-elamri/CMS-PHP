<?php
namespace App\Controllers;

use App\Repositories\PostRepository;
use App\Core\View;
use App\Middleware\AuthMiddleware;  // Ajout du middleware
use App\Core\Validator;

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
    
        public function create() {
            // Vérifiez que l'utilisateur est authentifié si nécessaire
            // (Ajoutez ici votre logique d'authentification)
    
            // Vérifiez si la requête est en POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
                // Initialisation du Validator avec les données POST
                $validator = new Validator($_POST);
    
                // Ajout des règles de validation dynamiques
                $validator
                    ->addRule('title', 'required', [], "Le titre est obligatoire.")
                    ->addRule('title', 'min', ['min' => 5], "Le titre doit contenir au moins 5 caractères.")
                    ->addRule('content', 'required', [], "Le contenu est obligatoire.")
                    ->addRule('content', 'min', ['min' => 3], "Le contenu doit avoir au moins 3 caractères.")
                    ->addRule('author_id', 'required', [], "L'ID de l'auteur est requis.")
                    ->addRule('author_id', 'min', ['min' => 1], "L'ID de l'auteur doit être un nombre valide.")
                    ->addRule('file', 'fileType', [
                        'types' => ['image/jpeg', 'image/png', 'image/gif', 'application/pdf']
                    ], "Le fichier doit être une image (JPG, PNG, GIF) ou un PDF.")
                    ->addRule('file', 'fileSize', [
                        'maxSize' => 2 * 1024 * 1024
                    ], "Le fichier ne doit pas dépasser 2 Mo.");
    
                // On lance la validation
                if ($validator->validate()) {
    
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
    
                        // Déplacement du fichier vers le répertoire de destination
                        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                            $filePath = $uploadFile;
                        } else {
                            echo "Erreur lors du téléchargement du fichier.";
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
                    if ($postId) {
                        // Redirection après création avec un message de succès
                        header('Location: /post/list?success=1');
                        exit;
                    } else {
                        echo "Erreur lors de la création du post.";
                    }
                } else {
                    // Si la validation échoue, on récupère les erreurs
                    $errors = $validator->getErrors();
                    View::render('post/create', ['errors' => $errors]);
                }
            } else {
                // Afficher le formulaire de création d'un post
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
