# Contrôleurs API REST

## Structure de Base

### Contrôleur API de Base
class ApiController
{
    protected UserRepository $userRepository;
    protected ValidationService $validator;
    protected ResponseFormatter $formatter;

    public function __construct(
        UserRepository $userRepository,
        ValidationService $validator,
        ResponseFormatter $formatter
    ) {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->formatter = $formatter;
    }

    protected function json($data, int $status = 200, array $headers = []): Response
    {
        return $this->formatter->json($data, $status, $headers);
    }

    protected function error(string $message, int $status = 400): Response
    {
        return $this->formatter->error($message, $status);
    }
}

### Contrôleur Spécifique
class UserController extends ApiController
{
    public function index(Request $request): Response
    {
        try {
            $users = $this->userRepository->findAll(
                $request->query->get('page', 1),
                $request->query->get('limit', 10)
            );

            return $this->json([
                'data' => $users,
                'meta' => [
                    'total' => $this->userRepository->count(),
                    'page' => $request->query->get('page', 1)
                ]
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}

## Gestion des Requêtes

### Traitement des Données Entrantes
public function store(Request $request): Response
{
    try {
        // Récupération des données JSON
        $data = $request->getJson();

        // Validation des données
        $this->validator->validate($data, [
            'email' => 'required|email',
            'name' => 'required|min:2',
            'password' => 'required|min:8'
        ]);

        // Création de l'utilisateur
        $user = $this->userRepository->create($data);

        // Réponse avec code 201 (Created)
        return $this->json($user, 201, [
            'Location' => "/api/users/{$user->id}"
        ]);

    } catch (ValidationException $e) {
        return $this->error($e->getMessage(), 422);
    } catch (\Exception $e) {
        return $this->error('Une erreur est survenue', 500);
    }
}

### Gestion des Fichiers
public function uploadAvatar(Request $request): Response
{
    try {
        $file = $request->files->get('avatar');
        
        if (!$file) {
            throw new ValidationException('Aucun fichier fourni');
        }

        // Validation du fichier
        $this->validator->validateFile($file, [
            'mimes' => ['jpg', 'png'],
            'max_size' => '2M'
        ]);

        // Traitement et stockage
        $path = $this->fileService->store($file, 'avatars');

        return $this->json(['path' => $path]);

    } catch (ValidationException $e) {
        return $this->error($e->getMessage(), 422);
    }
}

## Validation des Données

### Service de Validation
class ValidationService
{
    public function validate(array $data, array $rules): void
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $fieldRules = explode('|', $fieldRules);
            
            foreach ($fieldRules as $rule) {
                if (!$this->validateRule($data[$field] ?? null, $rule)) {
                    $errors[$field][] = $this->getErrorMessage($field, $rule);
                }
            }
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }

    protected function validateRule($value, string $rule): bool
    {
        // Implémentation des règles de validation
        return match ($rule) {
            'required' => !empty($value),
            'email' => filter_var($value, FILTER_VALIDATE_EMAIL) !== false,
            default => true,
        };
    }
}

### Utilisation dans le Contrôleur
public function update(int $id, Request $request): Response
{
    try {
        $data = $request->getJson();

        $this->validator->validate($data, [
            'email' => 'email',
            'name' => 'min:2',
            'status' => 'in:active,inactive'
        ]);

        $user = $this->userRepository->update($id, $data);
        return $this->json($user);

    } catch (ValidationException $e) {
        return $this->error($e->getErrors(), 422);
    }
}

## Formatage des Réponses

### Service de Formatage
class ResponseFormatter
{
    public function json($data, int $status = 200, array $headers = []): Response
    {
        $response = [
            'success' => $status < 400,
            'data' => $data
        ];

        return new Response(
            json_encode($response),
            $status,
            array_merge(['Content-Type' => 'application/json'], $headers)
        );
    }

    public function error(string|array $message, int $status = 400): Response
    {
        $response = [
            'success' => false,
            'error' => [
                'message' => $message,
                'code' => $status
            ]
        ];

        return new Response(
            json_encode($response),
            $status,
            ['Content-Type' => 'application/json']
        );
    }

    public function paginate(array $data, int $total, int $page, int $limit): array
    {
        return [
            'data' => $data,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'last_page' => ceil($total / $limit)
            ]
        ];
    }
}

### Exemples de Réponses

#### Succès
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2024-01-20T15:30:45Z"
    }
}

#### Erreur
{
    "success": false,
    "error": {
        "message": "Validation failed",
        "code": 422,
        "details": {
            "email": ["Format d'email invalide"],
            "password": ["Minimum 8 caractères requis"]
        }
    }
}

#### Liste Paginée
{
    "success": true,
    "data": [
        { "id": 1, "name": "John" },
        { "id": 2, "name": "Jane" }
    ],
    "meta": {
        "total": 50,
        "page": 1,
        "limit": 10,
        "last_page": 5
    }
} 