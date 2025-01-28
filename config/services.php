<?php

use Core\Database\Database;
use App\Services\UserService;
use App\Services\AuthService;
use App\Models\Repository\UserRepository;
use App\Controllers\Auth\AuthController;
use App\Controllers\Admin\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CorsMiddleware;

return [
    // Services de base
    Database::class => function($container) {
        return Database::getInstance([
            'host' => $_ENV['DB_HOST'] ?? 'db',
            'dbname' => $_ENV['DB_DATABASE'] ?? 'pharmacy',
            'user' => $_ENV['DB_USERNAME'] ?? 'edu',
            'password' => $_ENV['DB_PASSWORD'] ?? '6333'
        ]);
    },

    // Repositories
    UserRepository::class => function($container) {
        return new UserRepository();
    },

    // Services
    UserService::class => function($container) {
        return new UserService($container->get(Database::class));
    },

    AuthService::class => function($container) {
        return new AuthService(
            $container->get(UserRepository::class)
        );
    },

    // Controllers
    AuthController::class => function($container) {
        return new AuthController(
            $container->get(AuthService::class),
            $container->get(UserRepository::class)
        );
    },

    UserController::class => function($container) {
        return new UserController(
            $container->get(AuthService::class),
            $container->get(UserRepository::class)
        );
    },

    // Middlewares
    'auth.middleware' => function($container) {
        return new AuthMiddleware(
            $container->get(AuthService::class),
            ['redirect' => '/login']
        );
    },

    'cors.middleware' => function($container) {
        return new CorsMiddleware();
    }
];