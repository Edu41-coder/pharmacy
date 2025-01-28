<?php

namespace Core\Exception;

/**
 * Classe de base pour toutes les exceptions personnalisées
 */
class Exception extends \Exception
{
    /**
     * Données supplémentaires liées à l'exception
     */
    protected array $context = [];

    /**
     * Type d'erreur pour un meilleur traitement
     */
    protected string $errorType = 'general';

    /**
     * Constructeur étendu
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        string $errorType = "general",
        ?\Throwable $previous = null,
        array $context = []
    ) {
        $this->errorType = $errorType;
        $this->context = $this->prepareContext($context, $previous);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Prépare le contexte de l'exception avec des informations supplémentaires
     */
    protected function prepareContext(array $context, ?\Throwable $previous): array
    {
        return array_merge([
            'timestamp' => date('Y-m-d H:i:s'),
            'previous_error' => $previous ? [
                'message' => $previous->getMessage(),
                'code' => $previous->getCode(),
                'file' => $previous->getFile(),
                'line' => $previous->getLine()
            ] : null,
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
            'request_uri' => $_SERVER['REQUEST_URI'] ?? null,
            'request_method' => $_SERVER['REQUEST_METHOD'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
        ], $context);
    }

    /**
     * Récupère le type d'erreur
     */
    public function getErrorType(): string
    {
        return $this->errorType;
    }

    /**
     * Récupère le contexte complet de l'exception
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Ajoute des données au contexte
     */
    public function addContext(string $key, $value): self
    {
        $this->context[$key] = $value;
        return $this;
    }

    /**
     * Ajoute plusieurs données au contexte
     */
    public function addContextData(array $data): self
    {
        $this->context = array_merge($this->context, $data);
        return $this;
    }

    /**
     * Récupère une donnée spécifique du contexte
     */
    public function getContextData(string $key): mixed
    {
        return $this->context[$key] ?? null;
    }

    /**
     * Vérifie si une donnée existe dans le contexte
     */
    public function hasContextData(string $key): bool
    {
        return isset($this->context[$key]);
    }

    /**
     * Formate l'exception pour le logging
     */
    public function __toString(): string
    {
        return sprintf(
            "[%s] [%s] %s in %s:%d\nStack trace:\n%s\nContext: %s",
            date('Y-m-d H:i:s'),
            $this->errorType,
            $this->message,
            $this->file,
            $this->line,
            $this->getTraceAsString(),
            json_encode($this->context, JSON_PRETTY_PRINT)
        );
    }

    /**
     * Crée une représentation structurée de l'exception
     */
    public function toArray(): array
    {
        return [
            'error' => true,
            'type' => $this->errorType,
            'message' => $this->message,
            'code' => $this->code,
            'file' => $this->file,
            'line' => $this->line,
            'context' => $this->context
        ];
    }

    /**
     * Crée une représentation pour l'affichage utilisateur
     */
    public function toDisplay(): array
    {
        return [
            'error' => true,
            'type' => $this->errorType,
            'message' => $this->message,
            'code' => $this->code
        ];
    }

    /**
     * Détermine si l'exception doit être loggée
     */
    public function shouldBeLogged(): bool
    {
        return true;
    }

    /**
     * Détermine si l'exception doit être rapportée
     */
    public function shouldBeReported(): bool
    {
        return true;
    }
} 