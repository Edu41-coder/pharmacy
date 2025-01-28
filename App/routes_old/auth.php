<?php

use App\Controllers\AuthController;

$controller = new AuthController();

switch ($action) {
    case 'auth_login':
        $controller->login();
        break;
    case 'auth_register':
        $controller->register();
        break;
    case 'auth_logout':
        $controller->logout();
        break;
}