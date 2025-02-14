<?php
use App\Core\Router;

// On appelle les méthodes statiques directement sur la classe Router
Router::add('GET', '/', 'HomeController@index', 'AuthMiddleware@handle');

Router::add('GET', '/post/create', 'HomeController@create', 'RoleMiddleware@handleAdmin');
Router::add('POST', '/post/create', 'HomeController@create', 'AuthMiddleware@handle');
Router::add('GET', '/post/list', 'HomeController@list', 'AuthMiddleware@handle');
Router::add('GET', '/post/{id}', 'HomeController@show', 'AuthMiddleware@handle');
Router::add('GET', '/post/list/{page}', 'HomeController@list', 'AuthMiddleware@handle');
Router::add('POST', '/post/edit/{id}', 'HomeController@edit', 'RoleMiddleware@handleAdmin');
Router::add('GET', '/post/edit/{id}', 'HomeController@edit', 'RoleMiddleware@handleAdmin');
Router::add('POST', '/post/delete/{id}', 'HomeController@delete', 'RoleMiddleware@handleAdmin');


Router::add('GET', '/article/create', 'ArticleController@create', 'AuthMiddleware@handle');
Router::add('POST', '/article/store', 'ArticleController@store', 'AuthMiddleware@handle');
Router::add('GET', '/article/edit/{id}', 'ArticleController@edit', 'AuthMiddleware@handle');
Router::add('POST', '/article/edit/{id}', 'ArticleController@update', 'AuthMiddleware@handle');
Router::add('GET', '/article/delete/{id}', 'ArticleController@delete', 'AuthMiddleware@handle');



// Routes pour l'authentification
Router::add('GET', '/login', 'AuthController@loginForm');
Router::add('POST', '/login', 'AuthController@login');
Router::add('GET', '/logout', 'AuthController@logout');
Router::add('GET', '/register', 'AuthController@registerForm');
Router::add('POST', '/register', 'AuthController@register');

// Route pour l'erreur 404
Router::add('GET', '/404', 'ErrorController@notFound');

return Router::getRoutes();
