# Gestion des Erreurs dans une API REST

## Exceptions Personnalisées

### Exception de Base
class ApiException extends \Exception
{
    protected array $errors = [];
    protected int $statusCode = 500;

    public function __construct(
        string $message = "",
        int $code = 0,
        array $errors = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
        $this->statusCode = $code ?: $this->statusCode;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}

### Exceptions Spécifiques
class ValidationException extends ApiException
{
    protected int $statusCode = 422;

    public function __construct(array $errors, string $message = "Validation failed")
    {
        parent::__construct($message, $this->statusCode, $errors);
    }
}

class NotFoundException extends ApiException
{
    protected int $statusCode = 404;

    public function __construct(string $resource = "Resource")
    {
        parent::__construct("{$resource} not found", $this->statusCode);
    }
}

class UnauthorizedException extends ApiException
{
    protected int $statusCode = 401;

    public function __construct(string $message = "Unauthorized")
    {
        parent::__construct($message, $this->statusCode);
    }
}

## Gestionnaire d'Erreurs Global

### Handler d'Exceptions
class ExceptionHandler
{
    private ResponseFormatter $formatter;

    public function __construct(ResponseFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function handle(\Throwable $e): Response
    {
        return match(true) {
            $e instanceof ValidationException => $this->handleValidationException($e),
            $e instanceof NotFoundException => $this->handleNotFoundException($e),
            $e instanceof UnauthorizedException => $this->handleUnauthorizedException($e),
            $e instanceof ApiException => $this->handleApiException($e),
            default => $this->handleException($e)
        };
    }

    private function handleValidationException(ValidationException $e): Response
    {
        return $this->formatter->error([
            'message' => $e->getMessage(),
            'errors' => $e->getErrors()
        ], $e->getStatusCode());
    }

    private function handleNotFoundException(NotFoundException $e): Response
    {
        return $this->formatter->error([
            'message' => $e->getMessage()
        ], $e->getStatusCode());
    }

    private function handleUnauthorizedException(UnauthorizedException $e): Response
    {
        return $this->formatter->error([
            'message' => $e->getMessage()
        ], $e->getStatusCode());
    }

    private function handleApiException(ApiException $e): Response
    {
        return $this->formatter->error([
            'message' => $e->getMessage(),
            'errors' => $e->getErrors()
        ], $e->getStatusCode());
    }

    private function handleException(\Throwable $e): Response
    {
        // Log l'erreur pour le débogage
        error_log($e->getMessage());
        error_log($e->getTraceAsString());

        return $this->formatter->error([
            'message' => 'Une erreur interne est survenue'
        ], 500);
    }
}

## Formatage des Erreurs

### Format Standard des Erreurs
{
    "success": false,
    "error": {
        "message": "Message d'erreur principal",
        "code": 400,
        "details": {
            "field1": ["Message d'erreur pour field1"],
            "field2": ["Message d'erreur pour field2"]
        }
    }
}

### Service de Formatage
class ErrorFormatter
{
    public function format(\Throwable $e): array
    {
        $response = [
            'success' => false,
            'error' => [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]
        ];

        if ($e instanceof ValidationException) {
            $response['error']['details'] = $e->getErrors();
        }

        if ($_ENV['APP_DEBUG'] === true) {
            $response['error']['debug'] = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
        }

        return $response;
    }
}

## Codes HTTP Appropriés

### Guide des Codes d'Erreur
- 400 Bad Request : Requête mal formée
- 401 Unauthorized : Non authentifié
- 403 Forbidden : Non autorisé
- 404 Not Found : Ressource non trouvée
- 422 Unprocessable Entity : Validation échouée
- 429 Too Many Requests : Rate limit dépassé
- 500 Internal Server Error : Erreur serveur
- 503 Service Unavailable : Service indisponible

### Utilisation dans le Code
try {
    // Code qui peut lever une exception
} catch (ValidationException $e) {
    return new Response($e->getMessage(), 422);
} catch (UnauthorizedException $e) {
    return new Response($e->getMessage(), 401);
} catch (NotFoundException $e) {
    return new Response($e->getMessage(), 404);
} catch (\Exception $e) {
    return new Response('Internal Server Error', 500);
}

## Exemples d'Erreurs Courantes

### Erreur de Validation
{
    "success": false,
    "error": {
        "message": "Validation failed",
        "code": 422,
        "details": {
            "email": [
                "L'email est requis",
                "Format d'email invalide"
            ],
            "password": [
                "Le mot de passe doit faire au moins 8 caractères"
            ]
        }
    }
}

### Ressource Non Trouvée
{
    "success": false,
    "error": {
        "message": "User not found",
        "code": 404
    }
}

### Non Autorisé
{
    "success": false,
    "error": {
        "message": "Unauthorized: Invalid token",
        "code": 401
    }
}

### Erreur Serveur
{
    "success": false,
    "error": {
        "message": "Une erreur interne est survenue",
        "code": 500
    }
} 