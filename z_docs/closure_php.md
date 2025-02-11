# Les Closures en PHP

Une closure en PHP est une fonction anonyme qui peut capturer et utiliser des variables de son contexte parent. 

## Exemple dans notre Router

Dans notre système de routage, nous utilisons une closure pour accéder au container dans le groupe de routes :

$router->group('/auth', function(Router $router) use ($container) {
    // La closure a accès à $container grâce au mot-clé 'use'
    $router->get('/login', [AuthController::class, 'showLoginForm']);
});

## Comparaison avec et sans closure

### Sans closure (fonction normale)

function defineAuthRoutes($router) {
    // ❌ Pas accès à $container ici !
    $router->get('/login', [AuthController::class, 'showLoginForm']);
}

$container = new Container();
defineAuthRoutes($router);  // La fonction ne peut pas accéder à $container

### Avec closure

$container = new Container();

$router->group('/auth', function(Router $router) use ($container) {
    // ✅ La closure a accès à $container grâce au 'use'
    $router->get('/login', [AuthController::class, 'showLoginForm']);
});

## Points clés sur les closures

1. C'est une fonction anonyme (sans nom)
2. Le mot-clé `use` permet de capturer des variables externes
3. Les variables capturées sont disponibles dans la fonction
4. Très utile pour le passage de contexte et la création de callbacks

## Exemple simple

$message = "Bonjour";

// Sans closure
function direBonjour() {
    // ❌ Pas accès à $message
    echo $message;  // Erreur !
}

// Avec closure
$direBonjourClosure = function() use ($message) {
    // ✅ Accès à $message grâce au 'use'
    echo $message;  // Affiche "Bonjour"
};

## Avantages des closures

1. **Portée** : Accès aux variables du contexte parent
2. **Flexibilité** : Peut être passée comme argument ou stockée dans une variable
3. **Encapsulation** : Les variables capturées sont isolées
4. **Lisibilité** : Code plus clair et plus maintenable
