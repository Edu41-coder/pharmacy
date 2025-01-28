<?php

use App\Controllers\ProduitController;

$controller = new ProduitController();

switch ($action) {
    case 'index':
        $controller->index();
        break;
    // Ajoutez d'autres actions par défaut si nécessaire
    default:
        $controller->index();
        break;
}