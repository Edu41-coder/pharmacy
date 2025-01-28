<?php

namespace Core\Exception;

/**
 * Exception spécifique pour les erreurs de contrôleur
 */
class ControllerException extends \Exception
{
    /**
     * Codes d'erreur spécifiques aux contrôleurs
     */
    public const ERROR_BAD_REQUEST = 400;
    public const ERROR_UNAUTHORIZED = 401;
    public const ERROR_FORBIDDEN = 403;
    public const ERROR_NOT_FOUND = 404;
    public const ERROR_METHOD_NOT_ALLOWED = 405;
    public const ERROR_CONFLICT = 409;
    public const ERROR_VALIDATION = 422;
    public const ERROR_SERVER = 500;

    /**
     * Type d'erreur pour un meilleur traitement
     */
    private string $errorType;

    /**
     * Constructeur personnalisé
     */
    public function __construct(string $message = "", int $code = 500, string $errorType = "server")
    {
        $this->errorType = $errorType;
        parent::__construct($message, $code);
    }

    /**
     * Récupère le type d'erreur
     */
    public function getErrorType(): string
    {
        return $this->errorType;
    }

    /**
     * Crée une exception pour une requête invalide
     */
    public static function badRequest(string $message = "Requête invalide"): self
    {
        return new self($message, self::ERROR_BAD_REQUEST, "bad_request");
    }

    /**
     * Crée une exception pour un accès non autorisé
     */
    public static function unauthorized(string $message = "Accès non autorisé"): self
    {
        return new self($message, self::ERROR_UNAUTHORIZED, "unauthorized");
    }

    /**
     * Crée une exception pour un accès interdit
     */
    public static function forbidden(string $message = "Accès interdit"): self
    {
        return new self($message, self::ERROR_FORBIDDEN, "forbidden");
    }

    /**
     * Crée une exception pour une ressource non trouvée
     */
    public static function notFound(string $message = "Ressource non trouvée"): self
    {
        return new self($message, self::ERROR_NOT_FOUND, "not_found");
    }

    /**
     * Crée une exception pour une méthode non autorisée
     */
    public static function methodNotAllowed(string $message = "Méthode non autorisée"): self
    {
        return new self($message, self::ERROR_METHOD_NOT_ALLOWED, "method_not_allowed");
    }

    /**
     * Crée une exception pour un conflit
     */
    public static function conflict(string $message = "Conflit avec une ressource existante"): self
    {
        return new self($message, self::ERROR_CONFLICT, "conflict");
    }

    /**
     * Crée une exception pour une erreur de validation
     */
    public static function validation(string $message = "Données invalides"): self
    {
        return new self($message, self::ERROR_VALIDATION, "validation");
    }

    /**
     * Crée une exception pour une erreur serveur
     */
    public static function serverError(string $message = "Erreur interne du serveur"): self
    {
        return new self($message, self::ERROR_SERVER, "server");
    }

    /**
     * Vérifie si l'erreur est liée à l'authentification
     */
    public function isAuthError(): bool
    {
        return in_array($this->code, [
            self::ERROR_UNAUTHORIZED,
            self::ERROR_FORBIDDEN
        ]);
    }

    /**
     * Vérifie si l'erreur est liée à la validation
     */
    public function isValidationError(): bool
    {
        return $this->code === self::ERROR_VALIDATION;
    }

    /**
     * Vérifie si l'erreur est une erreur serveur
     */
    public function isServerError(): bool
    {
        return $this->code >= 500;
    }

    /**
     * Retourne les données formatées pour le template
     */
    public function toArray(): array
    {
        return [
            'error' => true,
            'type' => $this->errorType,
            'code' => $this->code,
            'message' => $this->message
        ];
    }
} 