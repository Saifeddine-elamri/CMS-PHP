<?php
namespace App\Core;

class Router {
    // Propriété statique pour stocker les routes
    protected static $routes = [];


    // Ajoute une route à la liste avec des middlewares optionnels
    public static function add($method, $uri, $action, $middlewares = null) {
        // Si aucun middleware n'est fourni, on initialise un tableau vide
        if ($middlewares === null) {
            $middlewares = [];
        } elseif (!is_array($middlewares)) {
            // Si un seul middleware est passé sous forme de chaîne, le mettre dans un tableau
            $middlewares = [$middlewares];
        }

        self::$routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middlewares' => $middlewares // On stocke les middlewares associés à la route
        ];
    }

        // Dispatch la requête vers le bon contrôleur et méthode
        public static function dispatch() {
            // Récupérer l'URI de la requête et la méthode HTTP
            $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $requestMethod = $_SERVER['REQUEST_METHOD'];

            // Parcourir les routes pour trouver la correspondance
            foreach (self::$routes as $route) {
                // Convertir la route en expression régulière pour gérer les paramètres dynamiques
                $pattern = '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $route['uri']) . '$#';

                // Vérifier si la méthode et l'URI correspondent à la requête
                if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                    // Extrait les paramètres dynamiques
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                    // Exécuter les middlewares associés à cette route
                    if (!empty($route['middlewares'])) {
                        self::runMiddlewares($route['middlewares']);
                    }

                    // Divise l'action en contrôleur et méthode
                    list($controller, $method) = explode('@', $route['action']);

                    // Instancie le contrôleur et appelle la méthode
                    $controller = "App\\Controllers\\$controller";
                    $controllerInstance = new $controller();
                    call_user_func_array([$controllerInstance, $method], $params);
                    return;
                }
            }

            // Si aucune route ne correspond, affiche une erreur 404
            header("HTTP/1.0 404 Not Found");
            $controller = new \App\Controllers\ErrorController();
            $controller->notFound();
        }
            

    // Exécute un middleware
            public static function runMiddlewares($middlewares) {
                foreach ($middlewares as $middleware) {
                    // Vérifie si le middleware est une chaîne sous forme "ClassName@methodName"
                    if (is_string($middleware)) {
                        list($class, $method) = explode('@', $middleware);  // Divise la classe et la méthode
            
                        // Assure-toi que la classe est bien formatée
                        $class = "App\\Middleware\\$class";  // Préfixe le namespace de la classe
                        $middlewareInstance = new $class();  // Crée une instance de la classe
            
                        // Vérifie si la méthode existe
                        if (method_exists($middlewareInstance, $method)) {
                            $middlewareInstance->$method();  // Appelle la méthode
                        } else {
                            throw new Exception("La méthode $method n'existe pas dans le middleware $class");
                        }
                    } elseif (is_callable($middleware)) {
                        $middleware();  // Exécuter le middleware si c'est un callable
                    }
                }
            }
    




}
