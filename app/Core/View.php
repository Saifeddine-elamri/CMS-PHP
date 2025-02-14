<?php
namespace App\Core;
use App\Core\Template;

class View {

    public static function doRender($viewName, $data = []) {
        // Extraire les données à la vue
        extract($data);
        // Inclure la vue demandée
        include __DIR__ . '/../views/' . $viewName . '.php';

    }

    public static function render($viewName, $data = []) {
        // Créer une instance du moteur de template (Template)
        $template = new Template();
    
        // Extraire les données à la vue pour les rendre accessibles
        extract($data);
    
        // Appeler la méthode render du Template et inclure la vue demandée
        // La variable $data contient déjà toutes les données nécessaires, donc il suffit de la passer
        echo $template->render($viewName, $data);
    }
    
}
