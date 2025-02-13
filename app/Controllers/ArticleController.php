<?php
namespace App\Controllers;

use App\Models\Article;
use App\Services\ArticleService;
use App\Middleware\AuthMiddleware;

class ArticleController {
    protected $articleService;

    public function __construct() {
        $this->articleService = new ArticleService();
    }

    public function show($id) {
        $article = $this->articleService->getArticleById($id);
        include __DIR__ . '/../Views/article/show.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $this->articleService->createArticle($title, $content);
            header('Location: /articles');
        }
        include __DIR__ . '/../Views/article/create.php';
    }
}