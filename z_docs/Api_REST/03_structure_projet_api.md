# Structure d'un Projet API REST en PHP

## Organisation des Dossiers

Structure recommandée pour une API REST :

/project-root
    /src
        /Controllers
            /Api
                UserController.php
                ProductController.php
        /Models
            User.php
            Product.php
        /Services
            AuthService.php
            ValidationService.php
        /Repositories
            UserRepository.php
            ProductRepository.php
        /Exceptions
            ApiException.php
            ValidationException.php
        /Middleware
            AuthMiddleware.php
            CorsMiddleware.php
    /config
        database.php
        app.php
        cors.php
    /public
        index.php
        .htaccess
    /tests
        /Unit
        /Integration
    /vendor
    composer.json
    .env
    .env.example

## Autoloading

Configuration de Composer pour l'autoloading PSR-4 :

composer.json :
{
    "name": "your-vendor/api-project",
    "description": "REST API Project",
    "type": "project",
    "require": {
        "php": "^8.1",
        "vlucas/phpdotenv": "^5.5",
        "firebase/php-jwt": "^6.0"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    }
}

## Configuration

### Fichier .env
DATABASE_HOST=localhost
DATABASE_NAME=api_db
DATABASE_USER=root
DATABASE_PASS=secret
JWT_SECRET=your_jwt_secret_key
API_DEBUG=true
CORS_ALLOWED_ORIGINS=http://localhost:3000

### config/database.php
return [
    'driver' => 'mysql',
    'host' => $_ENV['DATABASE_HOST'],
    'database' => $_ENV['DATABASE_NAME'],
    'username' => $_ENV['DATABASE_USER'],
    'password' => $_ENV['DATABASE_PASS'],
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];

### config/cors.php
return [
    'allowed_origins' => explode(',', $_ENV['CORS_ALLOWED_ORIGINS']),
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    'allowed_headers' => ['Content-Type', 'Authorization'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];

### public/index.php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Gérer les erreurs en mode API
error_reporting(E_ALL);
ini_set('display_errors', $_ENV['API_DEBUG'] ?? false);

// Créer l'application
$app = new App\Application();

// Démarrer l'application
$app->run();

### public/.htaccess
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]

## Points Importants

1. **Séparation des Responsabilités**
   - Controllers : Gestion des requêtes/réponses
   - Services : Logique métier
   - Repositories : Accès aux données
   - Models : Entités et validation

2. **Sécurité**
   - Fichiers sensibles hors du dossier public
   - Variables d'environnement pour les configurations
   - Gestion des CORS

3. **Maintenabilité**
   - Structure modulaire
   - Autoloading standardisé
   - Configuration centralisée

4. **Testabilité**
   - Structure adaptée aux tests
   - Séparation claire des responsabilités
   - Injection de dépendances 