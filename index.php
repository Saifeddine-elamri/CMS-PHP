<?php
use App\Core\Router;
use App\Core\Database;
use App\Core\Container;
use App\Controllers\AuthController;
use App\Services\AuthService;
use App\Repositories\UserRepository;
use App\Repositories\PostRepository;

use App\Core\Session;
use App\Core\Http;
use App\Core\Validator;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/routes.php';

$config = require __DIR__ . '/config/database.php';

Database::init($config);


$container = new Container();

// Pas besoin d'enregistrer chaque service manuellement. Le conteneur le fait automatiquement via `make()`
$container->set('AuthService', new AuthService());
$container->set('UserRepository', new UserRepository());
$container->set('PostRepository', new PostRepository());

// Chargement de la base de donné et de routeur
Router::setContainer($container);

Router::dispatch();






