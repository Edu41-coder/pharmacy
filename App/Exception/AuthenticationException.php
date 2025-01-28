class AuthenticationException extends \Exception
{
    public static function invalidCredentials(): self
    {
        return new self("Identifiants invalides");
    }

    public static function sessionExpired(): self
    {
        return new self("Votre session a expiré");
    }

    public static function insufficientPermissions(): self
    {
        return new self("Permissions insuffisantes");
    }
} 