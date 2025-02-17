<?php

use App\Core\Router;
use App\Core\Database;
use App\Core\Container;
use App\Repositories\PostRepository;
use App\Repositories\PostRepositoryInterface;

// Charger l'autoload de Composer
require __DIR__ . '/vendor/autoload.php';

// Charger la configuration de la base de données
$config = require __DIR__ . '/config/database.php';

// Initialiser la connexion à la base de données
Database::init($config);

// Créer une instance du conteneur de dépendances
$container = new Container();

// Associer l'interface à l'implémentation
$container->set(PostRepositoryInterface::class, new PostRepository());

// Configurer le routeur pour utiliser le conteneur
Router::setContainer($container);

// Charger les routes depuis le fichier de configuration
require __DIR__ . '/config/routes.php';

// Démarrer le dispatching des routes
Router::dispatch();
