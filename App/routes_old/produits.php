<?php

use App\Controllers\ProduitController;

$controller = new ProduitController();

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        checkAuthentication(); // Vérifier l'authentification
        $controller->create();
        break;
    case 'show':
        checkAuthentication(); // Vérifier l'authentification
        if ($id) {
            $controller->read($id);
        } else {
            $controller->index();
        }
        break;
    case 'edit':
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
    default:
        $controller->index();
        break;
}