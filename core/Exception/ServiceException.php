<?php

namespace Core\Exception;

/**
 * Exception spécifique aux services
 */
class ServiceException extends Exception
{
    /**
     * Codes d'erreur spécifiques aux services
     */
    public const ERROR_VALIDATION = 2001;
    public const ERROR_NOT_FOUND = 2002;
    public const ERROR_UNAUTHORIZED = 2003;
    public const ERROR_BUSINESS_RULE = 2004;
    public const ERROR_EXTERNAL_SERVICE = 2005;
    public const ERROR_OPERATION_FAILED = 2006;

    /**
     * Erreurs de validation
     */
    protected array $errors = [];

    /**
     * Type de service concerné
     */
    protected string $serviceType;

    /**
     * Action qui a échoué
     */
    protected string $action;

    /**
     * Constructeur étendu
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        string $serviceType = "",
        string $action = "",
        array $errors = [],
        ?\Throwable $previous = null,
        array $context = []
    ) {
        $this->errors = $errors;
        $this->serviceType = $serviceType;
        $this->action = $action;

        // Enrichir le contexte
        $context = array_merge([
            'service_type' => $serviceType,
            'action' => $action,
            'validation_errors' => $errors
        ], $context);

        parent::__construct($message, $code, 'service', $previous, $context);
    }

    /**
     * Récupère les erreurs de validation
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Ajoute une erreur de validation
     */
    public function addError(string $field, string $message): self
    {
        $this->errors[$field] = $message;
        $this->context['validation_errors'] = $this->errors;
        return $this;
    }

    /**
     * Récupère le type de service
     */
    public function getServiceType(): string
    {
        return $this->serviceType;
    }

    /**
     * Récupère l'action qui a échoué
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Vérifie si une erreur existe pour un champ
     */
    public function hasError(string $field): bool
    {
        return isset($this->errors[$field]);
    }

    /**
     * Récupère le message d'erreur pour un champ
     */
    public function getError(string $field): ?string
    {
        return $this->errors[$field] ?? null;
    }

    /**
     * Crée une exception pour une erreur de validation
     */
    public static function validationError(
        string $serviceType,
        array $errors,
        string $message = "Erreur de validation"
    ): self {
        return new self(
            $message,
            self::ERROR_VALIDATION,
            $serviceType,
            'validate',
            $errors
        );
    }

    /**
     * Crée une exception pour une entité non trouvée
     */
    public static function notFound(
        string $serviceType,
        string $entityType,
        mixed $identifier,
        ?string $message = null
    ): self {
        $message ??= sprintf("L'entité %s avec l'identifiant %s n'a pas été trouvée", $entityType, (string)$identifier);
        
        return new self(
            $message,
            self::ERROR_NOT_FOUND,
            $serviceType,
            'get',
            [],
            null,
            [
                'entity_type' => $entityType,
                'identifier' => $identifier
            ]
        );
    }

    /**
     * Crée une exception pour une règle métier violée
     */
    public static function businessRuleViolation(
        string $serviceType,
        string $rule,
        string $message,
        array $context = []
    ): self {
        return new self(
            $message,
            self::ERROR_BUSINESS_RULE,
            $serviceType,
            'validate_rule',
            [],
            null,
            array_merge(['rule' => $rule], $context)
        );
    }

    /**
     * Crée une exception pour une opération non autorisée
     */
    public static function unauthorized(
        string $serviceType,
        string $action,
        string $message = "Opération non autorisée"
    ): self {
        return new self(
            $message,
            self::ERROR_UNAUTHORIZED,
            $serviceType,
            $action
        );
    }

    /**
     * Crée une exception pour une erreur de service externe
     */
    public static function externalServiceError(
        string $serviceType,
        string $externalService,
        string $message,
        ?\Throwable $previous = null
    ): self {
        return new self(
            $message,
            self::ERROR_EXTERNAL_SERVICE,
            $serviceType,
            'external_call',
            [],
            $previous,
            ['external_service' => $externalService]
        );
    }

    /**
     * Détermine si l'erreur est une erreur de validation
     */
    public function isValidationError(): bool
    {
        return $this->code === self::ERROR_VALIDATION;
    }

    /**
     * Détermine si l'erreur est une erreur "not found"
     */
    public function isNotFoundError(): bool
    {
        return $this->code === self::ERROR_NOT_FOUND;
    }

    /**
     * Détermine si l'erreur est une erreur d'autorisation
     */
    public function isUnauthorizedError(): bool
    {
        return $this->code === self::ERROR_UNAUTHORIZED;
    }
} 