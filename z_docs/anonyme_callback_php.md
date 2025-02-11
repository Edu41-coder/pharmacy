echo '# Fonctions Anonymes et Callbacks en PHP

## 1. Fonction Anonyme

Une fonction anonyme est une fonction sans nom qui peut être stockée dans une variable ou passée comme argument.

    // Fonction normale (nommée)
    function addition($a, $b) {
        return $a + $b;
    }

    // Fonction anonyme
    $addition = function($a, $b) {
        return $a + $b;
    };

    // Les deux sutilisent différemment :
    addition(2, 3);        // Appel de la fonction nommée
    $addition(2, 3);       // Appel de la fonction anonyme

## 2. Callbacks en PHP vs JavaScript

### JavaScript (asynchrone)

    // Asynchrone avec callback
    fetchData((error, result) => {
        if (error) {
            console.error(error);
            return;
        }
        console.log(result);
    });

    // Le code continue pendant que fetchData sexécute
    console.log("Cette ligne sexécute avant le callback");

### PHP (synchrone)

    // Les callbacks en PHP sont synchrones
    array_map(function($item) {
        return $item * 2;
    }, [1, 2, 3]);

    // Le code attend que array_map finisse
    echo "Cette ligne sexécute après le callback";

## 3. Différences Principales

### JavaScript
- Asynchrone par nature
- Utilise souvent des callbacks pour gérer les opérations asynchrones
- Ne bloque pas lexécution du code
- Utilise des Promises, async/await

### PHP
- Synchrone par défaut
- Les callbacks sont utilisés pour passer des comportements
- Bloque lexécution jusquà ce que la fonction termine
- Pas de concept natif de Promise ou async/await

## 4. Exemple Concret dans Notre Application

    // Dans routes/web.php
    $router->group("/auth", function(Router $router) use ($container) {
        // Cette fonction anonyme est un callback
        // Mais elle sexécute immédiatement et de façon synchrone
        $router->get("/login", [AuthController::class, "showLoginForm"]);
    });

    // Ce code ne sexécute quaprès que le groupe soit complètement défini
    $router->get("/other-route", ...);

## Note Importante

PHP a des extensions comme ReactPHP ou Swoole pour gérer lasynchrone, mais par défaut, tout est synchrone. Ces extensions permettent davoir un comportement asynchrone similaire à JavaScript, mais ne sont pas nécessaires pour la plupart des applications web traditionnelles.' > z_docs/asynchrone_callback_php.md