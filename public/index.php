<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use app\Core\Router;

// Créez une instance de votre routeur
$router = new Router();

// Chargez les routes
require_once __DIR__ . '/../routes/web.php';

// Dispatchez la requête au contrôleur approprié
$router->dispatch();