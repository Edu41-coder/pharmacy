<?php

use App\Controllers\UserController;

$controller = new UserController();

switch ($action) {
    case 'index':
        checkAuthentication(); // Vérifier l'authentification
        $controller->index();
        break;
    case 'create':
        checkAuthentication(); // Vérifier l'authentification
        $controller->create();
        break;
    case 'store':
        checkAuthentication(); // Vérifier l'authentification
        $controller->store();
        break;
    case 'show':
        checkAuthentication(); // Vérifier l'authentification
        if ($id) {
            $controller->show($id);
        } else {
            $controller->index();
        }
        break;
    case 'edit':
        checkAuthentication(); // Vérifier l'authentification
        if ($id) {
            $controller->edit($id);
        } else {
            $controller->index();
        }
        break;
    case 'update':
        checkAuthentication(); // Vérifier l'authentification
        if ($id) {
            $controller->update($id);
        } else {
            $controller->index();
        }
        break;
    case 'delete':
        checkAuthentication(); // Vérifier l'authentification
        if ($id) {
            $controller->delete($id);
        } else {
            $controller->index();
        }
        break;
    case 'searchByRole':
        checkAuthentication(); // Vérifier l'authentification
        $controller->searchByRole();
        break;
    case 'searchByUsername':
        checkAuthentication(); // Vérifier l'authentification
        $controller->searchByUsername();
        break;
    case 'showRegisterForm':
        $controller->showRegisterForm();
        break;
    case 'register':
        $controller->register();
        break;
    case 'showLoginForm':
        $controller->showLoginForm();
        break;
    case 'login':
        $controller->login();
        break;
    case 'logout':
        $controller->logout();
        break;
    default:
        $controller->index();
        break;
}
?>