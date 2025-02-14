<?php
namespace App\Core;
use ReflectionMethod;
use App\Core\Http;

class Router {
    // Propriété statique pour stocker les routes
    protected static $routes = [];

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

        $foundRoute = false;  // Variable pour savoir si une route a été trouvée

        foreach (self::$routes as $route) {
            $pattern = '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $route['uri']) . '$#';

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (!empty($route['middlewares'])) {
                    self::runMiddlewares($route['middlewares']);
                }

                list($controller, $method) = explode('@', $route['action']);
                $controller = "App\\Controllers\\$controller";
                $controllerInstance = new $controller();

                // Vérifie les attributs HttpMethod sur la méthode pour POST, PUT, etc.
                if ($requestMethod !== 'GET') {
                    $methodReflection = new ReflectionMethod($controllerInstance, $method);
                    $httpMethodAttributes = $methodReflection->getAttributes(Http::class);
                    
                    foreach ($httpMethodAttributes as $attribute) {
                        $httpMethod = $attribute->newInstance();
                        
                        // Vérifie si la méthode HTTP correspond
                        if ($httpMethod->method === $requestMethod) {
                            call_user_func_array([$controllerInstance, $method], $params);
                            $foundRoute = true;
                            break;
                        }
                    }
                } else {
                    // Si la méthode est GET, on l'exécute directement sans passer par les attributs
                    call_user_func_array([$controllerInstance, $method], $params);
                    $foundRoute = true;
                }
                
                // Si une route a été trouvée, on l'exécute et on arrête la boucle
                if ($foundRoute) {
                    return;
                }
            }
        }

        // Si aucune route n'a été trouvée et que la méthode est GET, on ne fait rien
        if ($requestMethod === 'GET' && !$foundRoute) {
            return;  // Ne rien faire pour les requêtes GET si aucune route n'a été trouvée
        }

        // Sinon, on renvoie une erreur appropriée
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
