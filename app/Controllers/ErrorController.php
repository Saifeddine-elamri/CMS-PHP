<?php
namespace App\Controllers;
use App\Core\View;

class ErrorController {

    public function notFound() {
        // Render a 404 view
        View::render('error/404');
    }
}
