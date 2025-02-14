<?php
use App\Core\Router;
use App\Core\Database;
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/routes.php';

$config = require __DIR__ . '/config/database.php';


// Chargement de la base de donné et de routeur
Database::init($config);
Router::dispatch();