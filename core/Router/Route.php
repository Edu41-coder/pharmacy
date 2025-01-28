<?php

namespace Core\Router;

use Core\Http\Request;
use Core\Http\Response;
use Core\Middleware\MiddlewareInterface;
use Core\Container\Container;
use Core\Http\JsonResponse;

class Route
{
    private string $path;
    private $callable;
    private array $matches = [];
    private array $params = [];
    private array $middlewares = [];
    private static array $controllerCache = [];
    private ?Container $container = null;
    private ?string $name = null;

    public function __construct(string $path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    /**
     * Nomme la route
     */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Vérifie si l'URL correspond à la route
     */
    public function match(string $url): bool
    {
        $url = trim($url, '/');
        $path = preg_replace('#:{1}[^/]+#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";

        if (!preg_match($regex, $url, $matches)) {
            return false;
        }

        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    /**
     * Ajoute un paramètre avec une regex personnalisée
     */
    public function with(string $param, string $regex): self
    {
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this;
    }

    /**
     * Ajoute un middleware à la route
     */
    public function middleware($middleware): self
    {
        if ($middleware instanceof MiddlewareInterface) {
            $this->middlewares[] = $middleware;
        } elseif (is_string($middleware)) {
            $middlewareClass = "App\\Middleware\\{$middleware}Middleware";
            if (!class_exists($middlewareClass)) {
                throw RouterException::invalidCallback("Middleware '$middleware' non trouvé");
            }
            $this->middlewares[] = new $middlewareClass();
        }
        return $this;
    }

    /**
     * Ajoute plusieurs middlewares à la fois
     */
    public function middlewares(array $middlewares): self
    {
        foreach ($middlewares as $middleware) {
            $this->middleware($middleware);
        }
        return $this;
    }

    /**
     * Exécute la chaîne de middlewares
     */
    private function runMiddlewareStack(Request $request, callable $target): Response
    {
        // Créer la pile de middlewares
        $stack = array_reduce(
            array_reverse($this->middlewares), 
            function ($next, MiddlewareInterface $middleware) {
                return function (Request $request) use ($next, $middleware) {
                    // Vérifier si le middleware doit être exécuté
                    if ($middleware->shouldRun($request)) {
                        return $middleware->handle($request, $next);
                    }
                    return $next($request);
                };
            }, 
            function (Request $request) use ($target) {
                return $target($request);
            }
        );

        // Exécuter la pile
        return $stack($request);
    }

    /**
     * Exécute l'action associée à la route
     */
    public function call(Request $request, Response $response)
    {
        try {
            // Créer la fonction cible qui sera exécutée après les middlewares
            $target = function (Request $request) use ($response) {
                if (is_string($this->callable)) {
                    return $this->handleControllerCall($request, $response);
                }

                if (!is_callable($this->callable)) {
                    throw RouterException::invalidCallback('Callback non valide');
                }

                return call_user_func_array(
                    $this->callable,
                    array_merge([$request, $response], $this->matches)
                );
            };

            // Exécuter la chaîne de middlewares
            $result = $this->runMiddlewareStack($request, $target);

            // Gérer différents types de retour
            if ($result instanceof Response) {
                return $result;
            }

            if (is_array($result)) {
                return new JsonResponse($result);
            }

            return new Response($result);

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => $e->getMessage()
                ], $e->getCode() ?: 500);
            }
            throw $e;
        }
    }

    private function handleControllerCall(Request $request, Response $response)
    {
        $params = explode('@', $this->callable);
        if (count($params) !== 2) {
            throw RouterException::invalidCallback($this->callable);
        }

        [$controllerClass, $methodName] = $params;

        if (!class_exists($controllerClass)) {
            throw RouterException::controllerNotFound($controllerClass);
        }

        // Utiliser le container pour créer le contrôleur si disponible
        $controller = $this->container ? 
            $this->container->get($controllerClass) : 
            new $controllerClass();

        if (!method_exists($controller, $methodName)) {
            throw RouterException::methodNotFound($controllerClass, $methodName);
        }

        return call_user_func_array(
            [$controller, $methodName],
            array_merge([$request, $response], $this->matches)
        );
    }

    /**
     * Génère l'URL pour la route avec les paramètres donnés
     */
    public function getUrl(array $params = []): string
    {
        $path = $this->path;
        
        foreach ($params as $k => $v) {
            $path = str_replace(":$k", $v, $path);
        }
        
        return '/' . trim($path, '/');
    }

    private function paramMatch($match): string
    {
        if (isset($this->params[$match[1]])) {
            return '(' . $this->params[$match[1]] . ')';
        }
        return '([^/]+)';
    }

    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getController(): string
    {
        if (is_string($this->callable)) {
            $params = explode('@', $this->callable);
            return $params[0];
        }
        throw new RouterException('No controller defined for this route');
    }

    public function getAction(): string
    {
        if (is_string($this->callable)) {
            $params = explode('@', $this->callable);
            return $params[1];
        }
        throw new RouterException('No action defined for this route');
    }

    public function getParams(): array
    {
        return $this->matches;
    }
} 