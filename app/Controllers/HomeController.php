<?php
namespace App\Controllers;

use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use App\Core\View;
use App\Middleware\AuthMiddleware;  // Ajout du middleware
use App\Core\Validator;
use App\Core\Session;
use App\Core\Http;

class HomeController {

    protected $postRepository;
    protected $userRepository;

    public function __construct( PostRepository $postRepository, UserRepository $userRepository) {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;


    }

    // Méthode pour afficher tous les posts
    public function index() {
        // Vérifie si l'utilisateur est authentifié avant d'afficher les posts
        $isAdmin = Session::get('user_role') === 'admin';

        $posts = $this->postRepository->findAll();  // Récupérer tous les posts
        View::render('post/index', ['posts' => $posts,
        'isAdmin' => $isAdmin,
        'title' => 'Liste des Posts']);  // Afficher la vue avec les posts
    }

    public function list($page = 1) {
        $isAdmin = Session::get('user_role') === 'admin';
        $postsPerPage = 4;
        $search = isset($_GET['search']) ? $_GET['search'] : '';  // Récupère le paramètre de recherche de l'URL
        $totalPosts = $this->postRepository->getTotalPosts($search);  // Passe la recherche à la méthode getTotalPosts
        $totalPages = ceil($totalPosts / $postsPerPage);

        // Si la page actuelle est supérieure au nombre de pages, on la réajuste
        $currentPage = is_numeric($page) ? (int)$page : 1;
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $postsPerPage;

        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        // Récupérer les posts paginés en passant le paramètre de recherche
        $posts = $this->postRepository->findPaginated($offset, $postsPerPage);

        View::render('post/list', [
            'posts' => $posts,
            'isAdmin' => $isAdmin,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,

        ]);
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
        #[Http('POST')]
        public function create() {
            // Vérifiez que l'utilisateur est authentifié si nécessaire
            // (Ajoutez ici votre logique d'authentification)
    
            // Vérifiez si la requête est en POST
    
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
                        'maxSize' => 5 * 1024 * 1024
                    ], "Le fichier ne doit pas dépasser 5 Mo.");
    
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
            } 
        
        public function createRender() {
            $admins = $this->userRepository->getAdmins();  // Méthode pour récupérer les admins

            // Afficher le formulaire de création d'un post
            View::render('post/create', ['admins' => $admins]);
        }

        public function editForm($id) {
            if (Session::get('user_role') !== 'admin') {
                // Si ce n'est pas un admin, rediriger ou afficher une erreur
                header('Location: /');
                exit;
            }
            $post = $this->postRepository->findById($id);

            // Afficher le formulaire d'édition avec les données du post
            View::render('post/edit', ['post' => $post]);
        }

        #[Http('POST')]
        public function edit($id) {
            // Vérifie si l'utilisateur est authentifié et est un admin
            if (Session::get('user_role') !== 'admin') {
                // Si ce n'est pas un admin, rediriger ou afficher une erreur
                header('Location: /');
                exit;
            }
        
            // Récupérer le post par son ID pour l'afficher dans le formulaire d'édition
            $post = $this->postRepository->findById($id);
        
            if (!$post) {
                // Si le post n'existe pas, rediriger vers la liste des posts
                header('Location: /post/list');
                exit;
            }
        
            // Si la requête est en POST, traiter l'édition
                // Mettre à jour les informations du post
                $updatedData = [
                    'title' => $_POST['title'],
                    'content' => $_POST['content'],
                    // Gérer l'upload du fichier (image ou PDF)
                    'file_path' => isset($_FILES['file']) ? $this->handleFileUpload($_FILES['file']) : $post['file_path'], // Gérer l'upload du fichier
                ];
        
                // Mettre à jour le post dans la base de données
                $this->postRepository->update($id, $updatedData);
        
                // Rediriger vers la page des posts après la mise à jour
                header('Location: /post/list');
                exit;
            
        
        }
        
        // Méthode pour gérer l'upload des fichiers
        private function handleFileUpload($file) {
            if ($file['error'] !== UPLOAD_ERR_OK) {
                return null; // Pas de fichier téléchargé ou erreur
            }
        
            // Vérifier le type du fichier
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
            if (!in_array($file['type'], $allowedTypes)) {
                return null; // Format de fichier non autorisé
            }
        
            // Déplacer le fichier téléchargé dans le dossier approprié
            $uploadDir = 'public/uploads/';
            $filePath = $uploadDir . uniqid() . '-' . basename($file['name']);
        
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                return $filePath; // Retourner le chemin du fichier téléchargé
            }
        
            return null; // Si le téléchargement échoue, retourner null
        }
        

    // Méthode pour supprimer un post
    #[Http('POST')]
    public function delete($id) {
        // Vérifie si l'utilisateur est authentifié avant de permettre la suppression

        $affectedRows = $this->postRepository->delete($id);  // Supprimer le post dans la base de données
        // Redirection après suppression
        header('Location: /post/list');
        exit;
    }
}
