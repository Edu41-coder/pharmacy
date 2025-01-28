<?php

namespace Core\Router;

use Core\Http\Request;
use Core\Http\Response;
use Core\Http\JsonResponse;
use Core\Container\Container;

class Router implements RouterInterface
{
    private array $routes = [];
    private string $url;
    private array $namedRoutes = [];
    private string $prefix;
    private array $middlewares = [];
    private array $globalMiddlewares = [];
    private Container $container;

    public function __construct(string $url = '', string $prefix = '', Container $container)
    {
        $this->url = $this->parseUrl($url);
        $this->prefix = trim($prefix, '/');
        $this->container = $container;
    }

    /**
     * Nettoie et parse l'URL entrante
     * 
     * @param string $url URL à parser
     * @return string URL nettoyée
     */
    private function parseUrl(string $url): string
    {
        // Supprimer les paramètres GET
        $position = strpos($url, '?');
        if ($position !== false) {
            $url = substr($url, 0, $position);
        }

        // Supprimer le chemin de base si défini
        $basePath = $_ENV['BASE_PATH'] ?? '';
        if (!empty($basePath)) {
            $url = str_replace($basePath, '', $url);
        }

        // Nettoyer les slashes multiples et les slashes aux extrémités
        return trim(preg_replace('#/+#', '/', $url), '/');
    }

    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    /**
     * Ajoute une route GET
     * 
     * @param string $path Chemin de la route
     * @param mixed $callable Action à exécuter
     * @param string|null $name Nom de la route
     * @return Route
     */
    public function get(string $path, $callable, ?string $name = null): Route
    {
        return $this->addRoute('GET', $path, $callable, $name);
    }

    /**
     * Ajoute une route POST
     */
    public function post(string $path, $callable, ?string $name = null): Route
    {
        return $this->addRoute('POST', $path, $callable, $name);
    }

    /**
     * Ajoute une route PUT
     */
    public function put(string $path, $callable, ?string $name = null): Route
    {
        return $this->addRoute('PUT', $path, $callable, $name);
    }

    /**
     * Ajoute une route DELETE
     */
    public function delete(string $path, $callable, ?string $name = null): Route
    {
        return $this->addRoute('DELETE', $path, $callable, $name);
    }

    /**
     * Ajoute une route OPTIONS
     */
    public function options(string $path, $callable, ?string $name = null): Route
    {
        return $this->addRoute('OPTIONS', $path, $callable, $name);
    }

    /**
     * Ajoute une route pour toutes les méthodes HTTP
     */
    public function any(string $path, $callable, ?string $name = null): Route
    {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'];
        $routes = [];
        
        foreach ($methods as $method) {
            $routes[] = $this->addRoute($method, $path, $callable, $name);
        }
        
        return $routes[0]; // Retourne la première route créée
    }

    /**
     * Ajoute une route à la collection
     */
    private function addRoute(string $method, string $path, $callable, ?string $name): Route
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    /**
     * Exécute le routeur
     */
    public function run()
    {
        try {
            $request = new Request();
            $response = new Response();
            $method = $request->getMethod();

            if (!isset($this->routes[$method])) {
                if ($request->wantsJson()) {
                    return JsonResponse::error('Method Not Allowed', 405)->send();
                }
                throw RouterException::methodNotAllowed($method);
            }

            foreach ($this->routes[$method] as $route) {
                if ($route->match($this->url)) {
                    return $this->handleRoute($route, $request, $response);
                }
            }

            if ($request->wantsJson()) {
                return JsonResponse::error('Route Not Found', 404)->send();
            }
            throw RouterException::routeNotFound($this->url);

        } catch (RouterException $e) {
            if ($request->wantsJson()) {
                return JsonResponse::error($e->getMessage(), $e->getCode())->send();
            }
            throw $e;
        }
    }

    private function handleRoute(Route $route, Request $request, Response $response)
    {
        try {
            if ($this->container) {
                $route->setContainer($this->container);
            }

            $result = $route->call($request, $response);

            if ($result instanceof Response) {
                return $result->send();
            }

            if (is_array($result)) {
                return JsonResponse::success($result)->send();
            }

            return (new Response($result))->send();

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return JsonResponse::error($e->getMessage(), 500)->send();
            }
            throw $e;
        }
    }

    /**
     * Génère une URL à partir du nom de la route
     */
    public function url(string $name, array $params = []): string
    {
        if (!isset($this->namedRoutes[$name])) {
            throw RouterException::namedRouteNotFound($name);
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getNamedRoutes(): array
    {
        return $this->namedRoutes;
    }

    public function hasNamedRoute(string $name): bool
    {
        return isset($this->namedRoutes[$name]);
    }

    public function getCurrentUrl(): string
    {
        return $this->url;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Groupe des routes avec un préfixe commun
     */
    public function group(string $prefix, callable $callback): self
    {
        $previousPrefix = $this->prefix;
        $this->prefix .= '/' . trim($prefix, '/');
        
        $callback($this);
        
        $this->prefix = $previousPrefix;
        
        return $this;
    }

    public function middleware($middleware, array $params = []): self
    {
        $this->middlewares[] = [$middleware, $params];
        return $this;
    }

    public function addGlobalMiddleware($middleware, array $params = []): self
    {
        if (is_string($middleware)) {
            $this->globalMiddlewares[] = [$middleware, $params];
        }
        return $this;
    }

    public function dispatch(Request $request): Response
    {
        // Trouver la route
        $route = $this->findRoute($request);
        
        if (!$route) {
            throw new RouterException('Route not found');
        }

        // Résoudre le contrôleur via le container
        $controller = $this->resolveController($route['controller']);
        
        // Exécuter les middlewares
        $response = $this->runMiddlewares($request, function($request) use ($controller, $route) {
            return $controller->{$route['action']}($request);
        });

        return $response;
    }

    private function findRoute(Request $request): ?array
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        foreach ($this->routes[$method] ?? [] as $route) {
            if ($route->match($uri)) {
                return [
                    'controller' => $route->getController(),
                    'action' => $route->getAction(),
                    'params' => $route->getParams()
                ];
            }
        }

        return null;
    }

    private function runMiddlewares(Request $request, callable $next): Response
    {
        $middlewares = array_merge($this->globalMiddlewares, $this->middlewares);
        
        if (empty($middlewares)) {
            return $next($request);
        }

        $middleware = array_shift($middlewares);
        if (is_array($middleware)) {
            [$middleware, $params] = $middleware;
        }

        if (is_string($middleware)) {
            $middleware = $this->container->get($middleware);
        }

        return $middleware->handle($request, function($request) use ($middlewares, $next) {
            $this->middlewares = $middlewares;
            return $this->runMiddlewares($request, $next);
        });
    }

    private function resolveController(string $controller)
    {
        if ($this->container->has($controller)) {
            return $this->container->get($controller);
        }

        $class = "App\\Controllers\\$controller";
        return $this->container->get($class);
    }
} 