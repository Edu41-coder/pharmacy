<?php

namespace Core\Exception;

/**
 * Exception spécifique pour les erreurs de sécurité
 */
class SecurityException extends Exception
{
    /**
     * Codes d'erreur spécifiques à la sécurité
     */
    public const ERROR_CSRF = 3001;
    public const ERROR_SESSION = 3002;
    public const ERROR_PERMISSION = 3003;

    /**
     * Crée une exception pour une erreur CSRF
     */
    public static function invalidCsrfToken(string $message = "Token CSRF invalide"): self
    {
        return new self($message, self::ERROR_CSRF);
    }

    /**
     * Crée une exception pour une erreur de session
     */
    public static function sessionError(string $message = "Erreur de session"): self
    {
        return new self($message, self::ERROR_SESSION);
    }

    /**
     * Crée une exception pour une erreur de permission
     */
    public static function permissionDenied(string $message = "Permission refusée"): self
    {
        return new self($message, self::ERROR_PERMISSION);
    }
} 