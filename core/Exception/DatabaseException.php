<?php

namespace Core\Exception;

/**
 * Exception spécifique pour les erreurs de base de données
 */
class DatabaseException extends \Exception
{
    /**
     * Codes d'erreur spécifiques à la base de données
     */
    public const CONNECTION_ERROR = 1001;
    public const QUERY_ERROR = 1002;
    public const TRANSACTION_ERROR = 1003;
    public const PREPARE_ERROR = 1004;
    public const DUPLICATE_ENTRY = 1005;
    public const FOREIGN_KEY_ERROR = 1006;
    public const TABLE_NOT_FOUND = 1007;

    /**
     * Informations contextuelles sur l'erreur
     */
    private array $context;

    /**
     * Constructeur personnalisé pour les erreurs de base de données
     */
    public function __construct(
        string $message = "",
        int $code = self::CONNECTION_ERROR,
        ?\Throwable $previous = null,
        array $context = []
    ) {
        $this->context = $this->prepareContext($context, $previous);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Prépare le contexte de l'erreur
     */
    private function prepareContext(array $context, ?\Throwable $previous): array
    {
        return array_merge([
            'sql_state' => $previous?->getCode(),
            'driver_error' => $previous?->getMessage(),
            'timestamp' => date('Y-m-d H:i:s'),
            'query' => null,
            'parameters' => [],
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
        ], $context);
    }

    /**
     * Crée une exception pour une erreur de connexion
     */
    public static function connectionError(
        string $message = "Erreur de connexion à la base de données",
        ?\Throwable $previous = null,
        array $context = []
    ): self {
        return new self($message, self::CONNECTION_ERROR, $previous, $context);
    }

    /**
     * Crée une exception pour une erreur de requête
     */
    public static function queryError(
        string $message = "Erreur lors de l'exécution de la requête",
        string $query,
        array $parameters = [],
        ?\Throwable $previous = null
    ): self {
        return new self($message, self::QUERY_ERROR, $previous, [
            'query' => $query,
            'parameters' => $parameters
        ]);
    }

    /**
     * Crée une exception pour une erreur de transaction
     */
    public static function transactionError(
        string $message = "Erreur lors de la transaction",
        ?\Throwable $previous = null
    ): self {
        return new self($message, self::TRANSACTION_ERROR, $previous);
    }

    /**
     * Crée une exception pour une erreur de préparation de requête
     */
    public static function prepareError(
        string $message = "Erreur lors de la préparation de la requête",
        string $query,
        ?\Throwable $previous = null
    ): self {
        return new self($message, self::PREPARE_ERROR, $previous, ['query' => $query]);
    }

    /**
     * Crée une exception pour une entrée dupliquée
     */
    public static function duplicateEntry(
        string $message = "Cette entrée existe déjà",
        array $context = []
    ): self {
        return new self($message, self::DUPLICATE_ENTRY, null, $context);
    }

    /**
     * Récupère une donnée du contexte
     */
    protected function getContextData(string $key): mixed
    {
        return $this->context[$key] ?? null;
    }

    /**
     * Récupère la requête SQL si disponible
     */
    public function getQuery(): ?string
    {
        return $this->getContextData('query');
    }

    /**
     * Récupère les paramètres de la requête si disponibles
     */
    public function getParameters(): array
    {
        return $this->getContextData('parameters') ?? [];
    }

    /**
     * Récupère le code d'erreur SQL si disponible
     */
    public function getSqlState(): ?string
    {
        return $this->getContextData('sql_state');
    }

    /**
     * Récupère l'erreur du driver si disponible
     */
    public function getDriverError(): ?string
    {
        return $this->getContextData('driver_error');
    }

    /**
     * Récupère le timestamp de l'erreur
     */
    public function getTimestamp(): string
    {
        return $this->getContextData('timestamp');
    }

    /**
     * Récupère toutes les informations de contexte
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Formate l'exception pour le logging
     */
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'sql_state' => $this->getSqlState(),
            'driver_error' => $this->getDriverError(),
            'query' => $this->getQuery(),
            'parameters' => $this->getParameters(),
            'timestamp' => $this->getTimestamp(),
            'trace' => $this->context['trace'] ?? []
        ];
    }
} 