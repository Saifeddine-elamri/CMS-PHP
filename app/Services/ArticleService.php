<?php
namespace App\Services;

use App\Repositories\ArticleRepository;

class ArticleService {
    protected $articleRepository;

    public function __construct() {
        $this->articleRepository = new ArticleRepository();
    }

    public function getArticleById($id) {
        return $this->articleRepository->findById($id);
    }

    public function createArticle($title, $content) {
        return $this->articleRepository->create([
            'title' => $title,
            'content' => $content,
            'author_id' => $_SESSION['user_id']
        ]);
    }
}