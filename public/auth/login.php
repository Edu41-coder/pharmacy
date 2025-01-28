<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Pharmacie/vendor/autoload.php'; // Autoloading de Composer

use App\Controllers\UserController;

session_start();

$controller = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->login();
} else {
    $controller->showLoginForm();
}
?>