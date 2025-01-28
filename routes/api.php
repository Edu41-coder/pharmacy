<?php

use Core\Router\Router;

/** @var Router $router */

$router->group('/api', function(Router $router) {
    $router->get('/products', 'Api\ProductController@index');
    $router->post('/products', 'Api\ProductController@store');
}); 