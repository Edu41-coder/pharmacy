# CRUD Utilisateurs dans une API REST

## Configuration des Routes

routes/api.php :
$router->group('/api', function(Router $router) {
    $router->get('/users', [UserController::class, 'index']);
    $router->get('/users/{id}', [UserController::class, 'show']);
    $router->post('/users', [UserController::class, 'store']);
    $router->put('/users/{id}', [UserController::class, 'update']);
    $router->delete('/users/{id}', [UserController::class, 'destroy']);
});

## Implémentation du Contrôleur

class UserController extends ApiController
{
    private UserRepository $userRepository;
    private UserService $userService;

    public function __construct(UserRepository $userRepository, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    // GET /api/users
    public function index(Request $request): Response
    {
        try {
            $page = $request->query->get('page', 1);
            $limit = $request->query->get('limit', 10);
            $sort = $request->query->get('sort', 'id');
            $order = $request->query->get('order', 'asc');

            $users = $this->userRepository->findAll(
                page: $page,
                limit: $limit,
                sort: $sort,
                order: $order
            );

            $total = $this->userRepository->count();

            return $this->json([
                'data' => $users,
                'meta' => [
                    'total' => $total,
                    'page' => $page,
                    'limit' => $limit,
                    'last_page' => ceil($total / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            return $this->error('Erreur lors de la récupération des utilisateurs', 500);
        }
    }

    // GET /api/users/{id}
    public function show(int $id): Response
    {
        try {
            $user = $this->userRepository->find($id);

            if (!$user) {
                return $this->error('Utilisateur non trouvé', 404);
            }

            return $this->json([
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return $this->error('Erreur lors de la récupération de l\'utilisateur', 500);
        }
    }

    // POST /api/users
    public function store(Request $request): Response
    {
        try {
            $data = $request->getJson();

            // Validation
            $this->validator->validate($data, [
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'name' => 'required|min:2',
                'role' => 'in:user,admin'
            ]);

            // Création
            $user = $this->userService->create($data);

            return $this->json([
                'message' => 'Utilisateur créé avec succès',
                'data' => $user
            ], 201);

        } catch (ValidationException $e) {
            return $this->error($e->getErrors(), 422);
        } catch (\Exception $e) {
            return $this->error('Erreur lors de la création de l\'utilisateur', 500);
        }
    }

    // PUT /api/users/{id}
    public function update(int $id, Request $request): Response
    {
        try {
            $user = $this->userRepository->find($id);

            if (!$user) {
                return $this->error('Utilisateur non trouvé', 404);
            }

            $data = $request->getJson();

            // Validation
            $this->validator->validate($data, [
                'email' => "email|unique:users,email,{$id}",
                'name' => 'min:2',
                'role' => 'in:user,admin'
            ]);

            // Mise à jour
            $updatedUser = $this->userService->update($id, $data);

            return $this->json([
                'message' => 'Utilisateur mis à jour avec succès',
                'data' => $updatedUser
            ]);

        } catch (ValidationException $e) {
            return $this->error($e->getErrors(), 422);
        } catch (\Exception $e) {
            return $this->error('Erreur lors de la mise à jour de l\'utilisateur', 500);
        }
    }

    // DELETE /api/users/{id}
    public function destroy(int $id): Response
    {
        try {
            $user = $this->userRepository->find($id);

            if (!$user) {
                return $this->error('Utilisateur non trouvé', 404);
            }

            $this->userService->delete($id);

            return $this->json([
                'message' => 'Utilisateur supprimé avec succès'
            ], 200);

        } catch (\Exception $e) {
            return $this->error('Erreur lors de la suppression de l\'utilisateur', 500);
        }
    }
}

## Exemples de Requêtes et Réponses

### Liste des Utilisateurs
GET /api/users?page=1&limit=10&sort=name&order=asc

Réponse :
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Alice Smith",
            "email": "alice@example.com",
            "role": "admin",
            "created_at": "2024-01-20T10:00:00Z"
        },
        {
            "id": 2,
            "name": "Bob Johnson",
            "email": "bob@example.com",
            "role": "user",
            "created_at": "2024-01-20T11:00:00Z"
        }
    ],
    "meta": {
        "total": 50,
        "page": 1,
        "limit": 10,
        "last_page": 5
    }
}

### Détail d'un Utilisateur
GET /api/users/1

Réponse :
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Alice Smith",
        "email": "alice@example.com",
        "role": "admin",
        "created_at": "2024-01-20T10:00:00Z",
        "updated_at": "2024-01-20T10:00:00Z"
    }
}

### Création d'un Utilisateur
POST /api/users
Content-Type: application/json

{
    "name": "Charlie Brown",
    "email": "charlie@example.com",
    "password": "secret123",
    "role": "user"
}

Réponse :
{
    "success": true,
    "message": "Utilisateur créé avec succès",
    "data": {
        "id": 3,
        "name": "Charlie Brown",
        "email": "charlie@example.com",
        "role": "user",
        "created_at": "2024-01-20T12:00:00Z"
    }
}

### Mise à Jour d'un Utilisateur
PUT /api/users/3
Content-Type: application/json

{
    "name": "Charles Brown",
    "role": "admin"
}

Réponse :
{
    "success": true,
    "message": "Utilisateur mis à jour avec succès",
    "data": {
        "id": 3,
        "name": "Charles Brown",
        "email": "charlie@example.com",
        "role": "admin",
        "updated_at": "2024-01-20T12:30:00Z"
    }
}

### Suppression d'un Utilisateur
DELETE /api/users/3

Réponse :
{
    "success": true,
    "message": "Utilisateur supprimé avec succès"
} 