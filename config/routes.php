<?php
use App\Core\Router;

$router = new Router();

$router->add('GET', '/', 'HomeController@index', 'AuthMiddleware@handle');
$router->add('GET', '/post/create', 'HomeController@create', 'RoleMiddleware@handleAdmin');
$router->add('GET', '/post/list', 'HomeController@list', 'AuthMiddleware@handle');
$router->add('POST', '/post/create', 'HomeController@create', 'AuthMiddleware@handle');
$router->add('GET', '/article/create', 'ArticleController@create', 'AuthMiddleware@handle');
$router->add('POST', '/article/store', 'ArticleController@store', 'AuthMiddleware@handle');
$router->add('GET', '/article/edit/{id}', 'ArticleController@edit', 'AuthMiddleware@handle');
$router->add('POST', '/article/edit/{id}', 'ArticleController@update', 'AuthMiddleware@handle');
$router->add('GET', '/article/delete/{id}', 'ArticleController@delete', 'AuthMiddleware@handle');
$router->add('GET', '/post/{id}', 'HomeController@show', 'AuthMiddleware@handle');
// Exemple de route dans le routeur


$router->add('POST','/post/delete/{id}', 'HomeController@delete','RoleMiddleware@handleAdmin');
$router->add('POST','/post/edit/{id}', 'HomeController@edit','RoleMiddleware@handleAdmin');
$router->add('GET','/post/edit/{id}', 'HomeController@edit','RoleMiddleware@handleAdmin');

$router->add('GET', '/login', 'AuthController@loginForm');
$router->add('POST', '/login', 'AuthController@login');
$router->add('GET', '/logout', 'AuthController@logout');
$router->add('GET', '/register', 'AuthController@registerForm');
$router->add('POST', '/register', 'AuthController@register');
$router->add('GET', '/404', 'ErrorController@notFound');


return $router;
