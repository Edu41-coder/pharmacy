<?php

namespace Core\Exception;

use Exception;

/**
 * Exception spécifique pour les erreurs d'authentification
 */
class AuthenticationException extends Exception
{
    /**
     * Codes d'erreur spécifiques à l'authentification
     */
    public const INVALID_CREDENTIALS = 401;
    public const ACCOUNT_LOCKED = 423;  // Changed from 402 to standard 423 Locked
    public const ACCOUNT_NOT_VERIFIED = 403;
    public const ACCOUNT_DISABLED = 403;  // Changed from 404 to 403 Forbidden
    public const TOKEN_EXPIRED = 401;  // Changed from 405 to 401 Unauthorized
    public const TOKEN_INVALID = 401;  // Changed from 406 to 401 Unauthorized
    public const PASSWORD_EXPIRED = 401;  // Changed from 407 to 401 Unauthorized
    public const EMAIL_ALREADY_EXISTS = 409;
    public const INVALID_PASSWORD_FORMAT = 422;
    public const TOO_MANY_ATTEMPTS = 429;
    public const REGISTRATION_FAILED = 500;

    /**
     * Type d'erreur pour un meilleur traitement côté client
     */
    private string $errorType;

    /**
     * Constructeur personnalisé pour AuthenticationException
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        string $errorType = "credentials",
        ?Exception $previous = null
    ) {
        $this->errorType = $errorType;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Récupère le type d'erreur
     */
    public function getErrorType(): string
    {
        return $this->errorType;
    }

    // Factory methods
    public static function invalidCredentials(string $message = "Identifiants invalides"): self
    {
        return new self($message, self::INVALID_CREDENTIALS, "credentials");
    }

    public static function accountLocked(string $message = "Compte temporairement verrouillé"): self
    {
        return new self($message, self::ACCOUNT_LOCKED, "account");
    }

    public static function accountNotVerified(string $message = "Veuillez vérifier votre compte"): self
    {
        return new self($message, self::ACCOUNT_NOT_VERIFIED, "verification");
    }

    public static function accountDisabled(string $message = "Ce compte a été désactivé"): self
    {
        return new self($message, self::ACCOUNT_DISABLED, "account");
    }

    public static function tokenExpired(string $message = "Session expirée, veuillez vous reconnecter"): self
    {
        return new self($message, self::TOKEN_EXPIRED, "token");
    }

    public static function tokenInvalid(string $message = "Session invalide"): self
    {
        return new self($message, self::TOKEN_INVALID, "token");
    }

    public static function passwordExpired(string $message = "Votre mot de passe a expiré"): self
    {
        return new self($message, self::PASSWORD_EXPIRED, "password");
    }

    public static function emailAlreadyExists(string $message = "Cette adresse email est déjà utilisée"): self
    {
        return new self($message, self::EMAIL_ALREADY_EXISTS, "registration");
    }

    public static function invalidPasswordFormat(
        string $message = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre"
    ): self {
        return new self($message, self::INVALID_PASSWORD_FORMAT, "validation");
    }

    public static function tooManyAttempts(
        string $message = "Trop de tentatives, veuillez réessayer dans quelques minutes"
    ): self {
        return new self($message, self::TOO_MANY_ATTEMPTS, "security");
    }

    public static function registrationFailed(string $message = "L'inscription a échoué"): self
    {
        return new self($message, self::REGISTRATION_FAILED, "registration");
    }

    // Helper methods
    public function isCredentialsError(): bool
    {
        return $this->errorType === "credentials";
    }

    public function isTokenError(): bool
    {
        return $this->errorType === "token";
    }

    public function isSecurityError(): bool
    {
        return $this->errorType === "security";
    }

    public function isAccountError(): bool
    {
        return $this->errorType === "account";
    }

    public function isValidationError(): bool
    {
        return $this->errorType === "validation";
    }

    public function isRegistrationError(): bool
    {
        return $this->errorType === "registration";
    }

    /**
     * Retourne les données formatées pour l'API
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