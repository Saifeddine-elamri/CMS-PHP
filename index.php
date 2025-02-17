<?php

use App\Core\Router;
use App\Core\Database;

// Charger l'autoload de Composer
require __DIR__ . '/vendor/autoload.php';

// Charger la configuration de la base de données
$config = require __DIR__ . '/config/database.php';

// Initialiser la connexion à la base de données
Database::init($config);

// Charger les routes depuis le fichier de configuration
require __DIR__ . '/config/routes.php';

// Démarrer le dispatching des routes
Router::dispatch();
