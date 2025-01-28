<?php

namespace Core\Router;

use Exception;

class RouterException extends Exception
{
    public const ROUTE_NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const INVALID_CALLBACK = 500;
    public const NAMED_ROUTE_NOT_FOUND = 501;
    public const CONTROLLER_NOT_FOUND = 502;
    public const METHOD_NOT_FOUND = 503;

    public static function routeNotFound(string $url): self
    {
        return new self(
            sprintf('Aucune route ne correspond à l\'URL "%s"', $url),
            self::ROUTE_NOT_FOUND
        );
    }

    public static function methodNotAllowed(string $method): self
    {
        return new self(
            sprintf('La méthode HTTP "%s" n\'est pas autorisée pour cette route', $method),
            self::METHOD_NOT_ALLOWED
        );
    }

    public static function invalidCallback(string $callback): self
    {
        return new self(
            sprintf('Le callback "%s" n\'est pas valide', $callback),
            self::INVALID_CALLBACK
        );
    }

    public static function namedRouteNotFound(string $name): self
    {
        return new self(
            sprintf('Aucune route nommée "%s" n\'a été trouvée', $name),
            self::NAMED_ROUTE_NOT_FOUND
        );
    }

    public static function controllerNotFound(string $controller): self
    {
        return new self(
            sprintf('Le contrôleur "%s" n\'existe pas', $controller),
            self::CONTROLLER_NOT_FOUND
        );
    }

    public static function methodNotFound(string $controller, string $method): self
    {
        return new self(
            sprintf('La méthode "%s" n\'existe pas dans le contrôleur "%s"', $method, $controller),
            self::METHOD_NOT_FOUND
        );
    }
} 