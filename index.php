<?php


use App\Core\Router;
use App\Core\Database;
use App\Middleware\AuthMiddleware;
require __DIR__ . '/vendor/autoload.php';




// Charger la configuration et initialiser la base de donnée
$config = require __DIR__ . '/config/database.php';
Database::init($config);

// Charger le router et sa configuration et dispatcher les routes
require __DIR__ . '/config/routes.php';
Router::dispatch();