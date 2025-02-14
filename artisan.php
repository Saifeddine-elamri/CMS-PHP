#!/usr/bin/env php
<?php
// console

// Vérifier si on est bien en ligne de commande
if (php_sapi_name() !== 'cli') {
    echo "Cette commande doit être exécutée en ligne de commande.\n";
    exit;
}

// Récupérer la commande passée en argument
$command = $argv[1] ?? null;

// Vérifier si la commande est bien "making:controller" ou "making:model"
if (!in_array($command, ['making:controller', 'making:model','making:migration','migrate'])) {
    echo "Usage : php console making:controller ou php console making:model ou php console making:migration ou migrate \n";
    exit;
}

// Fonction de création de contrôleur
function createController() {
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

    $filename = "app/Controllers/{$controllerName}.php";

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

// Fonction de création de modèle et de repository associé
function createModelAndRepository() {
    echo "Entrez le nom du modèle (ex: User) : ";
    $handle = fopen("php://stdin", "r");
    $modelName = trim(fgets($handle));
    fclose($handle);

    if (empty($modelName)) {
        echo "Le nom du modèle ne peut pas être vide.\n";
        exit;
    }

    $modelFilename = "app/Models/{$modelName}.php";
    $repositoryFilename = "app/Repositories/{$modelName}Repository.php";

    // Vérifier si le fichier modèle existe déjà
    if (file_exists($modelFilename)) {
        echo "Erreur : Le modèle $modelName existe déjà.\n";
        exit;
    }

    // Vérifier si le fichier repository existe déjà
    if (file_exists($repositoryFilename)) {
        echo "Erreur : Le repository {$modelName}Repository existe déjà.\n";
        exit;
    }

    // Demander les propriétés du modèle
    echo "Entrez les propriétés du modèle (ex: name, email, etc.), séparées par des virgules : ";
    $handle = fopen("php://stdin", "r");
    $propertiesInput = trim(fgets($handle));
    fclose($handle);

    // Créer un tableau de propriétés
    $properties = array_map('trim', explode(',', $propertiesInput));

    // Template de la classe de base Model
    $modelTemplate = "<?php
// app/Models/Model.php

class Model {
    // Propriétés et méthodes communes pour tous les modèles
    public function save() {
        echo 'Sauvegarde de ' . get_class(\$this) . '\\n';
    }
}
?>";

    // Template du modèle spécifique
    $propertiesTemplate = '';
    foreach ($properties as $property) {
        $propertiesTemplate .= "    public \${$property};\n";
    }

    $modelSpecificTemplate = "<?php
// app/Models/{$modelName}.php
require 'Model.php';
class $modelName extends Model {
$propertiesTemplate

    public function __construct(" . implode(", ", array_map(fn($p) => "\$$p", $properties)) . ") {
        " . implode("\n        ", array_map(fn($p) => "\$this->$p = \$$p;", $properties)) . "
    }
}
?>";

    // Template de la classe Repository de base
    $repositoryTemplate = "<?php
// app/Repositories/Repository.php

class Repository {
    public function save(\$model) {
        if (!\$model instanceof Model) {
            throw new Exception('Le modèle doit être une instance de la classe Model');
        }
        // Logique pour sauvegarder l'instance du modèle
        echo 'Enregistrement de ' . get_class(\$model) . '\\n';
    }

    public function find(\$id) {
        // Logique pour trouver un modèle par son ID
        echo 'Trouver le modèle avec ID ' . \$id . '\\n';
    }
}
?>";

    // Template du repository spécifique
    $repositorySpecificTemplate = "<?php
// app/Repositories/{$modelName}Repository.php

class {$modelName}Repository extends Repository {
    // Logique spécifique pour ce modèle
}
?>";

    // Créer les fichiers de modèle et de repository
    if (!file_exists('app/Models')) {
        mkdir('app/Models', 0777, true);
    }

    if (!file_exists('app/Repositories')) {
        mkdir('app/Repositories', 0777, true);
    }

    // Créer le fichier Model de base si nécessaire
    if (!file_exists('app/Models/Model.php')) {
        file_put_contents('app/Models/Model.php', $modelTemplate);
    }

    // Créer le modèle spécifique
    file_put_contents($modelFilename, $modelSpecificTemplate);

    // Créer le repository spécifique
    file_put_contents($repositoryFilename, $repositorySpecificTemplate);

    echo "Modèle créé : $modelFilename\n";
    echo "Repository créé : $repositoryFilename\n";
}

// Fonction de création de migration basée sur le modèle avec du SQL pur

// Fonction de création de migration basée sur le modèle avec du SQL pur
function createMigrationFromModel() {
    echo "Entrez le nom du modèle existant (ex: Product) pour générer la migration : ";
    $handle = fopen("php://stdin", "r");
    $modelName = trim(fgets($handle));
    fclose($handle);

    if (empty($modelName)) {
        echo "Le nom du modèle ne peut pas être vide.\n";
        exit;
    }

    $modelFilename = "app/Models/{$modelName}.php";

    // Vérifier si le modèle existe
    if (!file_exists($modelFilename)) {
        echo "Erreur : Le modèle $modelName n'existe pas.\n";
        exit;
    }

    // Charger le modèle pour récupérer ses propriétés
    include $modelFilename;

    $reflection = new ReflectionClass($modelName);
    $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

    // Créer le nom de la migration
    $migrationName = "create_".strtolower($modelName)."_table";
    $migrationFilename = "database/migrations/{$migrationName}.php";

    // Vérifier si le fichier de migration existe déjà
    if (file_exists($migrationFilename)) {
        echo "Erreur : La migration $migrationName existe déjà.\n";
        exit;
    }

// Générer le code PHP de la migration avec SQL pur
$columns = '';
foreach ($properties as $property) {
    // Utilisation du nom de la propriété pour créer des colonnes
    $columns .= "`{$property->getName()}` VARCHAR(255),\n";
}

// Générer le code PHP pour la migration
$migrationTemplate = "<?php
// Migration pour créer la table {$modelName}

class Create{$modelName}Table {
    public function up(\$pdo) {
        // Requête SQL pour créer la table
        \$sql = \"CREATE TABLE IF NOT EXISTS `{$modelName}` (\n\";
        \$sql .= \"    `id` INT AUTO_INCREMENT PRIMARY KEY,\n\";
        \$sql .= \"    {$columns}\";
        \$sql .= \"    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,\n\";
        \$sql .= \"    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP\n\";
        \$sql .= \");\";
        
        // Exécution de la requête SQL
        \$pdo->exec(\$sql);
        echo 'Table `{$modelName}` créée avec succès.' . PHP_EOL;
    }

    public function down(\$pdo) {
        // Requête SQL pour supprimer la table
        \$sql = \"DROP TABLE IF EXISTS `{$modelName}`;\";
        \$pdo->exec(\$sql);
        echo 'Table `{$modelName}` supprimée avec succès.' . PHP_EOL;
    }
}
?>";


    // Créer le fichier de migration PHP
    if (!file_exists('database/migrations')) {
        mkdir('database/migrations', 0777, true);
    }

    file_put_contents($migrationFilename, $migrationTemplate);
    echo "Migration PHP créée : $migrationFilename\n";
}



// Exécution de la fonction correspondante en fonction de la commande
if ($command === 'making:controller') {
    createController();
} elseif ($command === 'making:model') {
    createModelAndRepository();
} elseif ($command === 'making:migration') {
    createMigrationFromModel();
}
elseif ($command === 'migrate') {
    // Inclure le fichier de migration
    require_once 'migrate.php';
    migrate(); // Appeler la fonction migrate
} 
