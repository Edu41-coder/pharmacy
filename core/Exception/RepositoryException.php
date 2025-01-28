<?php

namespace Core\Exception;

/**
 * Exception spécifique pour les erreurs liées aux repositories
 */
class RepositoryException extends Exception
{
    /**
     * Codes d'erreur spécifiques aux repositories
     */
    public const ERROR_NOT_FOUND = 1001;
    public const ERROR_CREATE = 1002;
    public const ERROR_UPDATE = 1003;
    public const ERROR_DELETE = 1004;
    public const ERROR_QUERY = 1005;
    public const ERROR_VALIDATION = 1006;
    public const ERROR_DUPLICATE = 1007;

    /**
     * Constructeur
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        array $context = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, 'repository', $previous, $context);
    }

    /**
     * Crée une exception pour une entité non trouvée
     */
    public static function notFound(string $entity, mixed $identifier): self
    {
        return new self(
            sprintf("L'entité %s avec l'identifiant %s n'a pas été trouvée", $entity, (string)$identifier),
            self::ERROR_NOT_FOUND,
            [
                'entity' => $entity,
                'identifier' => $identifier
            ]
        );
    }

    /**
     * Crée une exception pour une erreur de création
     */
    public static function createError(string $entity, string $reason, ?array $data = null): self
    {
        return new self(
            sprintf("Impossible de créer l'entité %s : %s", $entity, $reason),
            self::ERROR_CREATE,
            [
                'entity' => $entity,
                'reason' => $reason,
                'data' => $data
            ]
        );
    }

    /**
     * Crée une exception pour une erreur de mise à jour
     */
    public static function updateError(string $entity, mixed $identifier, string $reason): self
    {
        return new self(
            sprintf("Impossible de mettre à jour l'entité %s (%s) : %s", $entity, (string)$identifier, $reason),
            self::ERROR_UPDATE,
            [
                'entity' => $entity,
                'identifier' => $identifier,
                'reason' => $reason
            ]
        );
    }

    /**
     * Crée une exception pour une erreur de suppression
     */
    public static function deleteError(string $entity, mixed $identifier, string $reason): self
    {
        return new self(
            sprintf("Impossible de supprimer l'entité %s (%s) : %s", $entity, (string)$identifier, $reason),
            self::ERROR_DELETE,
            [
                'entity' => $entity,
                'identifier' => $identifier,
                'reason' => $reason
            ]
        );
    }

    /**
     * Crée une exception pour une erreur de requête
     */
    public static function queryError(
        string $entity,
        string $operation,
        string $reason,
        ?array $additionalContext = null
    ): self {
        return new self(
            sprintf("Erreur lors de l'opération %s sur l'entité %s : %s", $operation, $entity, $reason),
            self::ERROR_QUERY,
            [
                'entity' => $entity,
                'operation' => $operation,
                'reason' => $reason,
                'additional_context' => $additionalContext
            ]
        );
    }

    /**
     * Crée une exception pour une erreur de validation
     */
    public static function validationError(string $entity, array $errors): self
    {
        return new self(
            sprintf("Erreur de validation pour l'entité %s", $entity),
            self::ERROR_VALIDATION,
            [
                'entity' => $entity,
                'validation_errors' => $errors
            ]
        );
    }

    /**
     * Crée une exception pour une entrée dupliquée
     */
    public static function duplicateEntry(string $entity, string $field, mixed $value): self
    {
        return new self(
            sprintf("Une entité %s avec %s = %s existe déjà", $entity, $field, (string)$value),
            self::ERROR_DUPLICATE,
            [
                'entity' => $entity,
                'field' => $field,
                'value' => $value
            ]
        );
    }

    /**
     * Détermine si l'erreur est une erreur "not found"
     */
    public function isNotFoundError(): bool
    {
        return $this->code === self::ERROR_NOT_FOUND;
    }

    /**
     * Détermine si l'erreur est une erreur de validation
     */
    public function isValidationError(): bool
    {
        return $this->code === self::ERROR_VALIDATION;
    }

    /**
     * Détermine si l'erreur est une erreur de duplication
     */
    public function isDuplicateError(): bool
    {
        return $this->code === self::ERROR_DUPLICATE;
    }
} 