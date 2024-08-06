<?php

namespace app\Core;

class Router
{
    private $routes = [];

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($path, PHP_URL_PATH);

        $callback = $this->routes[$method][$path] ?? null;

        if ($callback === null) {
            // Gérer les routes non trouvées
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
            return;
        }

        if (is_string($callback)) {
            $parts = explode('@', $callback);
            $controller = "app\\Controllers\\" . $parts[0];
            $method = $parts[1];

            $controller = new $controller();
            $controller->$method();
        } else {
            call_user_func($callback);
        }
    }
}