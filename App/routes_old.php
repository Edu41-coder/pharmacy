<?php

// Autoloading de Composer
require_once $_SERVER['DOCUMENT_ROOT'] . '/Pharmacie/vendor/autoload.php'; // Chemin absolu basé sur la racine du serveur web

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
function checkAuthentication() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /Pharmacie/public/auth/login.php');
        exit();
    }
}

// Récupérer l'action depuis l'URL
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Inclure les fichiers de routes
if (strpos($action, 'auth') !== false) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Pharmacie/App/routes/auth.php';
} elseif (strpos($action, 'produits') !== false) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Pharmacie/App/routes/produits.php';
} elseif (strpos($action, 'utilisateurs') !== false) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Pharmacie/App/routes/utilisateurs.php';
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Pharmacie/App/routes/index.php';
}
?>