# Flux de création et utilisation du Container

Ah, bonne question ! Le **Container** est instancié dans le point d'entrée de l'application (`public/index.php`) et c'est lui qui gère toute l'injection des dépendances.

## Voici le flux :

D'abord dans `public/index.php` :

```php
<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Core\Container\Container;
use Core\Http\Request;
use Core\Router\Router;

// Créer le container
$container = new Container();

// Charger les services
$services = require dirname(__DIR__) . '/config/services.php';
foreach ($services as $id => $factory) {
    $container->set($id, $factory);
}

// Créer le router avec le container
$router = new Router($container);

// Charger les routes
require_once dirname(__DIR__) . '/routes/web.php';

// Créer la requête
$request = new Request();

// Dispatcher la requête
try {
    $response = $router->dispatch($request);
    $response->send();
} catch (\Exception $e) {
    // Gérer l'erreur
    error_log($e->getMessage());
    http_response_code(500);
    echo "Une erreur est survenue";
}
```

### Résolution du contrôleur

Quand une route est appelée, le **Router** utilise le container pour créer le contrôleur :

```php
private function resolveController(string $controller)
{
    if ($this->container->has($controller)) {
        return $this->container->get($controller);  // Le container crée l'instance avec ses dépendances
    }

    $class = "App\\Controllers\\$controller";
    return $this->container->get($class);
}
```

### Utilisation des services dans le container

Le **Container** utilise la configuration des services pour savoir comment créer les instances :

```php
AuthController::class => function($container) {
    return new AuthController(
        $container->get(AuthService::class),  // Crée AuthService
        $container->get(UserRepository::class) // Crée UserRepository
    );
},
```

### Processus de création des instances

Quand le **Container** crée une instance, il :
1. Regarde le constructeur de la classe.
2. Identifie les dépendances (type-hints).
3. Crée récursivement les dépendances nécessaires.
4. Injecte les dépendances dans le constructeur.

### Flux complet pour `AuthController`

Le flux complet pour **AuthController** est :

```
Request → Router → Container → AuthController
                    ↓
                    crée AuthService
                    crée UserRepository
                    injecte les deux dans AuthController
```

## Principe d'Inversion de Contrôle (IoC)

C'est ce qu'on appelle l'**Inversion de Contrôle (IoC)** : au lieu que les classes créent leurs dépendances, c'est le **Container** qui les crée et les injecte.
