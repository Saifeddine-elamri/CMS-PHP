<?php
require __DIR__ . '/vendor/autoload.php';

use App\Core\Database;
$config = require __DIR__ . '/config/database.php';


// Chargement de la base de donné et de routeur
Database::init($config);
$pdo = Database::getConnection();

// Fonction pour appliquer toutes les migrations
function migrate() {
    global $pdo;

    // Créer la table des migrations si elle n'existe pas
    $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration_name VARCHAR(255) NOT NULL,
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");

    // Récupérer tous les fichiers de migration dans le répertoire
    $migrationFiles = glob('database/migrations/*.php');

    if (empty($migrationFiles)) {
        echo "Aucune migration à appliquer.\n";
        return;
    }

    // Liste des migrations déjà appliquées
    $appliedMigrations = $pdo->query("SELECT migration_name FROM migrations")->fetchAll(PDO::FETCH_COLUMN);

    // Appliquer les migrations
    foreach ($migrationFiles as $migrationFile) {
        $migrationClass = getMigrationClass($migrationFile);

        // Si la migration a déjà été appliquée, on la saute
        if (in_array($migrationClass, $appliedMigrations)) {
            echo "Migration déjà appliquée : $migrationClass\n";
            continue;
        }

        // Inclure le fichier de migration
        include_once $migrationFile;

        if (class_exists($migrationClass)) {
            $migration = new $migrationClass();
            echo "Exécution de la migration : " . $migrationClass . "\n";
            $migration->up($pdo);  // Appeler la méthode up() pour exécuter la requête SQL

            // Enregistrer la migration dans la table `migrations`
            $stmt = $pdo->prepare("INSERT INTO migrations (migration_name) VALUES (?)");
            $stmt->execute([$migrationClass]);
        } else {
            echo "La classe $migrationClass n'existe pas dans le fichier $migrationFile.\n";
        }
    }
}

// Fonction pour obtenir le nom de la classe de migration à partir du fichier
function getMigrationClass($migrationFile) {
    // Extraire le nom du fichier sans l'extension .php
    $filename = pathinfo($migrationFile, PATHINFO_FILENAME);
    
    // Convertir le nom du fichier en PascalCase
    $className = str_replace(' ', '', ucwords(str_replace('_', ' ', $filename)));

    return $className;
}
