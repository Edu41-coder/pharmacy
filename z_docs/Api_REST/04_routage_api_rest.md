# Routage dans une API REST

## Définition des Routes

### Configuration de Base
routes/api.php :

$router->group('/api', function(Router $router) {
    // Routes utilisateurs
    $router->get('/users', [UserController::class, 'index']);
    $router->get('/users/{id}', [UserController::class, 'show']);
    $router->post('/users', [UserController::class, 'store']);
    $router->put('/users/{id}', [UserController::class, 'update']);
    $router->delete('/users/{id}', [UserController::class, 'destroy']);

    // Routes produits
    $router->get('/products', [ProductController::class, 'index']);
    $router->post('/products', [ProductController::class, 'store']);
});

### Groupes de Routes
$router->group('/api/admin', function(Router $router) {
    $router->get('/stats', [AdminController::class, 'stats']);
    $router->get('/logs', [AdminController::class, 'logs']);
})->middleware('auth.admin');

## Gestion des Méthodes HTTP

### Définition des Actions
class UserController extends ApiController
{
    // GET /users
    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        return $this->json($users);
    }

    // GET /users/{id}
    public function show(int $id): Response
    {
        $user = $this->userRepository->find($id);
        return $this->json($user);
    }

    // POST /users
    public function store(Request $request): Response
    {
        $data = $request->getJson();
        $user = $this->userService->create($data);
        return $this->json($user, 201);
    }

    // PUT /users/{id}
    public function update(int $id, Request $request): Response
    {
        $data = $request->getJson();
        $user = $this->userService->update($id, $data);
        return $this->json($user);
    }

    // DELETE /users/{id}
    public function destroy(int $id): Response
    {
        $this->userService->delete($id);
        return $this->json(null, 204);
    }
}

## Paramètres d'URL

### Paramètres de Route
$router->get('/users/{id}/posts/{postId}', function(int $id, int $postId) {
    // Les paramètres sont automatiquement injectés
    return "User $id, Post $postId";
});

### Paramètres Optionnels
$router->get('/users/{id?}', function(?int $id = null) {
    if ($id === null) {
        return "Liste des utilisateurs";
    }
    return "Détail de l'utilisateur $id";
});

### Paramètres avec Expressions Régulières
$router->get('/users/{id:[0-9]+}', function(int $id) {
    // Ne correspond qu'aux IDs numériques
});

### Paramètres de Requête (Query String)
// URL: /api/users?sort=name&order=desc
public function index(Request $request): Response
{
    $sort = $request->query->get('sort', 'id');
    $order = $request->query->get('order', 'asc');
    
    $users = $this->userRepository->findAll($sort, $order);
    return $this->json($users);
}

## Middleware de Routage

### Définition d'un Middleware
class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->isAuthenticated($request)) {
            return new Response('Unauthorized', 401);
        }

        return $next($request);
    }
}

### Application des Middlewares

#### Sur une Route Unique
$router->get('/profile', [UserController::class, 'profile'])
    ->middleware('auth');

#### Sur un Groupe de Routes
$router->group('/admin', function(Router $router) {
    $router->get('/dashboard', [AdminController::class, 'dashboard']);
    $router->get('/users', [AdminController::class, 'users']);
})->middleware(['auth', 'admin']);

#### Middleware Global
class Application
{
    public function __construct()
    {
        $this->router->middleware(new CorsMiddleware());
        $this->router->middleware(new JsonMiddleware());
    }
}

### Ordre des Middlewares
1. Middlewares globaux (CORS, JSON, etc.)
2. Middlewares de groupe
3. Middlewares de route
4. Contrôleur
5. Réponse remonte la chaîne

## Bonnes Pratiques

1. **Nommage des Routes**
   - Utiliser des noms explicites
   - Suivre les conventions REST
   - Éviter les verbes dans les URLs

2. **Versionnage**
   - Préfixer les routes avec la version (/api/v1/users)
   - Gérer la rétrocompatibilité

3. **Sécurité**
   - Valider tous les paramètres
   - Appliquer les middlewares appropriés
   - Gérer les CORS correctement

4. **Performance**
   - Mettre en cache les routes
   - Optimiser les expressions régulières
   - Limiter la profondeur des groupes 