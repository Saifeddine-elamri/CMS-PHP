<?php

namespace App\Core;
use ReflectionMethod;
use ReflectionParameter;
use App\Core\Http;

class Router {
    // Propriété statique pour stocker les routes
    protected static $routes = [];
    protected static $container;

    // Configure le conteneur
    public static function setContainer($container) {
        self::$container = $container;
    }

    // Récupère toutes les routes
    public static function getRoutes() {
        return self::$routes;
    }

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

    public static function dispatch() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $foundRoute = false;

        foreach (self::$routes as $route) {
            // Construire le pattern pour la route
            $pattern = '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $route['uri']) . '$#';

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Exécution des middlewares si définis pour cette route
                if (!empty($route['middlewares'])) {
                    self::runMiddlewares($route['middlewares']);
                }

                // Extraire le contrôleur et la méthode de la route
                list($controller, $method) = explode('@', $route['action']);
                $controller = "App\\Controllers\\$controller";

                // Utiliser le conteneur pour instancier le contrôleur
                $controllerInstance = self::$container->get($controller);

                // Utilisation de Reflection pour analyser les paramètres de la méthode
                $methodReflection = new ReflectionMethod($controllerInstance, $method);
                $methodParams = $methodReflection->getParameters();

                // Tableau pour stocker les arguments à passer à la méthode
                $args = [];

                // Parcourir les paramètres de la méthode pour déterminer les dépendances à injecter
                foreach ($methodParams as $param) {
                    $paramType = $param->getType();
                    
                    if ($paramType && !$paramType->isBuiltin()) {
                        // Si le paramètre est une classe, nous l'injectons depuis le conteneur
                        $className = $paramType->getName();
                        $dependency = self::$container->get($className);
                        $args[] = $dependency;
                    } else {
                        // Si ce n'est pas une dépendance, on passe la valeur de la route (paramètres dynamiques)
                        $args[] = isset($params[$param->getName()]) ? $params[$param->getName()] : null;
                    }
                }

                // Vérification des attributs HttpMethod pour POST, PUT, etc.
                if ($requestMethod !== 'GET') {
                    $httpMethodAttributes = $methodReflection->getAttributes(Http::class);

                    foreach ($httpMethodAttributes as $attribute) {
                        $httpMethod = $attribute->newInstance();

                        // Si la méthode HTTP correspond, on appelle la méthode avec les arguments
                        if ($httpMethod->method === $requestMethod) {
                            call_user_func_array([$controllerInstance, $method], $args);
                            $foundRoute = true;
                            break;
                        }
                    }
                } else {
                    // Si la méthode est GET, on l'exécute directement sans passer par les attributs HTTP
                    call_user_func_array([$controllerInstance, $method], $args);
                    $foundRoute = true;
                }

                // Si une route a été trouvée et exécutée, on arrête la boucle
                if ($foundRoute) {
                    return;
                }
            }
        }

        // Si aucune route n'a été trouvée pour la requête GET, ne rien faire
        if ($requestMethod === 'GET' && !$foundRoute) {
            return;  // On ne fait rien si aucune route n'est trouvée pour une requête GET
        }

        // Sinon, renvoyer une erreur appropriée
        if ($requestMethod !== 'GET') {
            header("HTTP/1.0 405 Method Not Allowed");
            echo "Méthode non autorisée pour cette action.";
        } else {
            header("HTTP/1.0 404 Not Found");
            $controller = new \App\Controllers\ErrorController();
            $controller->notFound();
        }
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
