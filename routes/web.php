<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\Admin\UserController;
use Core\Router\Router;
use App\Middleware\AuthMiddleware;
use App\Middleware\CorsMiddleware;
use Core\Container\Container;

/** @var Router $router */

// Middleware global
$router->middleware($container->get('cors.middleware'));

// Route principale
$router->get('/', 'HomeController@index');

// Routes d'authentification
$router->group('/auth', function(Router $router) {
    $router->get('/login', 'AuthController@showLoginForm')
        ->name('auth.login');
    $router->post('/login', 'AuthController@login')
        ->name('auth.login.post');
    $router->post('/logout', 'AuthController@logout')
        ->name('auth.logout');
});

// Routes protégées
$router->group('/dashboard', function(Router $router) {
    $router->get('/', 'HomeController@index');
})->middleware($container->get('auth.middleware'));

// Routes admin
$router->group('/admin', function(Router $router) {
    $router->get('/users', 'Admin\UserController@index');
})->middleware($container->get('auth.middleware')->withRoles(['admin']));