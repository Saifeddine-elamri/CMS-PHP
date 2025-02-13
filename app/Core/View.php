<?php
namespace App\Core;

class View {
    public static function render($viewName, $data = []) {
        // Extraire les données à la vue
        extract($data);
        // Inclure la vue demandée
        include __DIR__ . '/../views/' . $viewName . '.php';

    }
}
