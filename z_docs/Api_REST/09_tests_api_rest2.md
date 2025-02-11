# Tests d'API REST avec PHPUnit

## Configuration des Tests
Cette section établit la configuration de base pour PHPUnit, notamment la séparation entre tests unitaires et d'intégration.

### Configuration PHPUnit
phpunit.xml :
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         colors="true"
         bootstrap="vendor/autoload.php">
    <!-- Définition des suites de tests -->
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Integration">
            <directory>tests/Integration</directory>
        </testsuite>
    </testsuites>
    <!-- Variables d'environnement pour les tests -->
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_DATABASE" value="testing_db"/>
    </php>
</phpunit>

### TestCase de Base
Cette classe sert de fondation pour tous les tests d'API, offrant des fonctionnalités communes.

class ApiTestCase extends TestCase
{
    protected ?Application $app;
    protected ?PDO $db;

    // Méthode exécutée avant chaque test
    protected function setUp(): void
    {
        parent::setUp();
        
        // Initialisation de l'application et de la base de données
        $this->app = new Application();
        $this->db = $this->app->getContainer()->get(PDO::class);
        
        // Nettoyer la base de données avant chaque test
        $this->refreshDatabase();
    }

    // Réinitialise la base de données pour les tests
    protected function refreshDatabase(): void
    {
        $this->runMigrations();    // Exécute les migrations
        $this->seedTestData();     // Insère les données de test
    }

    // Crée un client HTTP authentifié pour les tests
    protected function createAuthenticatedClient(): TestClient
    {
        $client = new TestClient($this->app);
        $token = $this->getTestUserToken();
        $client->setHeader('Authorization', "Bearer {$token}");
        return $client;
    }
}

## Tests Unitaires
Les tests unitaires se concentrent sur des composants isolés, en utilisant des mocks pour simuler les dépendances.

### Test d'un Service
class UserServiceTest extends TestCase
{
    private UserService $userService;
    private MockObject $userRepository;

    protected function setUp(): void
    {
        // Création d'un mock du repository
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userService = new UserService($this->userRepository);
    }

    // Test de création d'utilisateur réussie
    public function testCreateUserSuccess(): void
    {
        // Arrange : Préparation des données
        $userData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'name' => 'Test User'
        ];

        // Configuration du mock
        $this->userRepository
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo($userData))
            ->willReturn(new User($userData));

        // Act : Exécution du test
        $user = $this->userService->create($userData);

        // Assert : Vérification des résultats
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@example.com', $user->email);
    }

    // Test de création avec email en doublon
    public function testCreateUserWithDuplicateEmail(): void
    {
        // Arrange
        $this->userRepository
            ->method('findByEmail')
            ->willReturn(new User());

        // Assert
        $this->expectException(ValidationException::class);

        // Act
        $this->userService->create([
            'email' => 'existing@example.com'
        ]);
    }
}

## Tests d'Intégration
Ces tests vérifient le fonctionnement de l'API de bout en bout, incluant les interactions entre composants.

### Test d'un Endpoint
class UserControllerTest extends ApiTestCase
{
    // Test de récupération de la liste des utilisateurs
    public function testGetUsersList(): void
    {
        // Arrange
        $client = $this->createAuthenticatedClient();

        // Act
        $response = $client->get('/api/users');

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertIsArray($data['data']);
    }

    // ... autres méthodes de test ...
}

## Points Clés à Retenir

1. **Organisation des Tests**
   - Séparation claire entre tests unitaires et d'intégration
   - Structure de base commune avec ApiTestCase
   - Environnement de test isolé

2. **Tests Unitaires**
   - Utilisation de mocks pour isoler les composants
   - Tests des cas de succès et d'erreur
   - Vérification précise des comportements

3. **Tests d'Intégration**
   - Test des endpoints complets
   - Vérification des réponses HTTP
   - Tests des interactions avec la base de données

4. **Mocking**
   - Simulation des repositories
   - Mock des services externes
   - Contrôle des comportements simulés

5. **Assertions**
   - Assertions personnalisées pour l'API
   - Vérification des structures JSON
   - Validation des erreurs 