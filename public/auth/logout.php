<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Pharmacie/vendor/autoload.php'; // Autoloading de Composer

use App\Controllers\UserController;

session_start();

$controller = new UserController();
$controller->logout();
?>