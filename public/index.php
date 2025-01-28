<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Core\Container\Container;
use Core\Http\Request;
use Core\Router\Router;
use Dotenv\Dotenv;

// Charger les variables d'environnement
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Créer le container
$container = new Container();

// Charger les services
$services = require dirname(__DIR__) . '/config/services.php';
foreach ($services as $id => $factory) {
    $container->set($id, $factory);
}

// Créer le router avec le container
$router = new Router($_SERVER['REQUEST_URI'] ?? '', '', $container);

// Charger les routes
require_once dirname(__DIR__) . '/routes/web.php';

// Créer la requête
$request = new Request();

// Dispatcher la requête
try {
    $response = $router->dispatch($request);
    $response->send();
} catch (\Exception $e) {
    // Gérer l'erreur
    error_log($e->getMessage());
    http_response_code(500);
    echo "Une erreur est survenue";
}