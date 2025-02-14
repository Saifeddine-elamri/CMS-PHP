<?php
// orm/Command.php

class Command {
    public static function run($argv) {
        $command = $argv[1] ?? null;

        if ($command === 'making:controller') {
            self::makeController();
        } else {
            echo "Commande inconnue.\n";
        }
    }

    private static function makeController() {
        // Demander le nom du contrôleur à l'utilisateur
        echo "Entrez le nom du contrôleur (ex: UserController) : ";
        $handle = fopen("php://stdin", "r");
        $controllerName = trim(fgets($handle));
        fclose($handle);

        if (empty($controllerName)) {
            echo "Le nom du contrôleur ne peut pas être vide.\n";
            exit;
        }

        // Vérifier si le nom se termine par "Controller"
        if (!str_ends_with($controllerName, 'Controller')) {
            $controllerName .= 'Controller';
        }

        $filename = "controllers/{$controllerName}.php";

        // Vérifier si le fichier existe déjà
        if (file_exists($filename)) {
            echo "Erreur : Le contrôleur $controllerName existe déjà.\n";
            exit;
        }

        // Template du contrôleur
        $template = "<?php
// $filename

class $controllerName {
    public function index() {
        echo 'Bienvenue dans $controllerName -> index()';
    }
}
?>";

        // Création du fichier de contrôleur
        file_put_contents($filename, $template);
        echo "Contrôleur créé : $filename\n";
    }
}

