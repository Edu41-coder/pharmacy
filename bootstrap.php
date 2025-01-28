<?php

use Dotenv\Dotenv;

// 1. Autoloading
require_once __DIR__ . '/vendor/autoload.php';

// 2. Chargement des helpers
require_once __DIR__ . '/core/Helpers/functions.php';

// 3. Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 4. Variables d'environnement
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// 5. Validation des variables requises
$dotenv->required([
    'APP_ENV',
    'APP_DEBUG',
    'BASE_PATH',
    'DB_HOST',
    'DB_PORT',
    'DB_NAME',
    'DB_USER',
    'DB_PASSWORD'
])->notEmpty();

// 6. Validation des types
$dotenv->required('APP_DEBUG')->isBoolean();
$dotenv->required('DB_PORT')->isInteger();

// 7. Configuration selon l'environnement
if (filter_var($_ENV['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN)) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// 8. Configuration de base
mb_internal_encoding('UTF-8');
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Europe/Paris');

// 9. Configuration Twig (si nécessaire)
if (!is_dir(__DIR__ . '/var/cache/twig')) {
    mkdir(__DIR__ . '/var/cache/twig', 0777, true);
}