<?php

namespace Core\Middleware;

/**
 * Exception spécifique pour les erreurs de middleware
 */
class MiddlewareException extends \Exception
{
    public const ERROR_UNAUTHORIZED = 401;
    public const ERROR_FORBIDDEN = 403;
    public const ERROR_INVALID_TOKEN = 498;
    public const ERROR_RATE_LIMIT = 429;
    public const ERROR_BAD_REQUEST = 400;

    /**
     * Crée une exception pour un accès non autorisé
     */
    public static function unauthorized(string $message = 'Non autorisé'): self
    {
        return new self($message, self::ERROR_UNAUTHORIZED);
    }

    /**
     * Crée une exception pour un accès interdit
     */
    public static function forbidden(string $message = 'Accès interdit'): self
    {
        return new self($message, self::ERROR_FORBIDDEN);
    }

    /**
     * Crée une exception pour un token invalide
     */
    public static function invalidToken(string $message = 'Token invalide ou expiré'): self
    {
        return new self($message, self::ERROR_INVALID_TOKEN);
    }

    /**
     * Crée une exception pour une limite de taux dépassée
     */
    public static function rateLimit(string $message = 'Trop de requêtes'): self
    {
        return new self($message, self::ERROR_RATE_LIMIT);
    }

    /**
     * Crée une exception pour une requête invalide
     */
    public static function badRequest(string $message = 'Requête invalide'): self
    {
        return new self($message, self::ERROR_BAD_REQUEST);
    }
} 