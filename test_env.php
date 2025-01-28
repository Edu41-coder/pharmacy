<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
try {
    $dotenv->load();
    echo "Le fichier .env a été chargé avec succès.<br>";
} catch (Exception $e) {
    echo "Erreur lors du chargement du fichier .env : " . $e->getMessage() . "<br>";
}

$dbHost = $_ENV['DB_HOST'] ?? null;
$dbUser = $_ENV['DB_USER'] ?? null;
$dbPass = $_ENV['DB_PASS'] ?? null;
$dbName = $_ENV['DB_NAME'] ?? null;

if (!$dbHost || !$dbUser || !$dbPass || !$dbName) {
    echo "Les variables d'environnement ne sont pas chargées correctement.<br>";
    echo "DB_HOST: " . ($dbHost ? $dbHost : 'Non défini') . "<br>";
    echo "DB_USER: " . ($dbUser ? $dbUser : 'Non défini') . "<br>";
    echo "DB_PASS: " . ($dbPass ? $dbPass : 'Non défini') . "<br>";
    echo "DB_NAME: " . ($dbName ? $dbName : 'Non défini') . "<br>";
} else {
    echo "Les variables d'environnement sont chargées correctement.<br>";
    echo "DB_HOST: $dbHost<br>";
    echo "DB_USER: $dbUser<br>";
    echo "DB_PASS: $dbPass<br>";
    echo "DB_NAME: $dbName<br>";
}