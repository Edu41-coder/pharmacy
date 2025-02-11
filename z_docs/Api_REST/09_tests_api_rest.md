# Tests d'API REST avec PHPUnit

## Configuration des Tests

### Configuration PHPUnit
phpunit.xml :
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         colors="true"
         bootstrap="vendor/autoload.php">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Integration">
            <directory>tests/Integration</directory>
        </testsuite>
    </testsuites>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_DATABASE" value="testing_db"/>
    </php>
</phpunit>

### TestCase de Base
class ApiTestCase extends TestCase
{
    protected ?Application $app;
    protected ?PDO $db;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->app = new Application();
        $this->db = $this->app->getContainer()->get(PDO::class);
        
        // Nettoyer la base de données
        $this->refreshDatabase();
    }

    protected function refreshDatabase(): void
    {
        $this->runMigrations();
        $this->seedTestData();
    }

    protected function createAuthenticatedClient(): TestClient
    {
        $client = new TestClient($this->app);
        $token = $this->getTestUserToken();
        $client->setHeader('Authorization', "Bearer {$token}");
        return $client;
    }
}

## Tests Unitaires

### Test d'un Service
class UserServiceTest extends TestCase
{
    private UserService $userService;
    private MockObject $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userService = new UserService($this->userRepository);
    }

    public function testCreateUserSuccess(): void
    {
        // Arrange
        $userData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'name' => 'Test User'
        ];

        $this->userRepository
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo($userData))
            ->willReturn(new User($userData));

        // Act
        $user = $this->userService->create($userData);

        // Assert
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@example.com', $user->email);
    }

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

### Test d'un Endpoint
class UserControllerTest extends ApiTestCase
{
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

    public function testCreateUser(): void
    {
        // Arrange
        $client = $this->createAuthenticatedClient();
        $userData = [
            'email' => 'new@example.com',
            'password' => 'password123',
            'name' => 'New User'
        ];

        // Act
        $response = $client->post('/api/users', $userData);

        // Assert
        $this->assertEquals(201, $response->getStatusCode());
        
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('new@example.com', $data['data']['email']);
        
        // Vérifier en base de données
        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com'
        ]);
    }

    public function testUpdateUserUnauthorized(): void
    {
        // Arrange
        $client = $this->createAuthenticatedClient('user');
        $adminUser = User::factory()->admin()->create();

        // Act
        $response = $client->put("/api/users/{$adminUser->id}", [
            'name' => 'Updated Name'
        ]);

        // Assert
        $this->assertEquals(403, $response->getStatusCode());
    }
}

## Mocking des Dépendances

### Mock d'un Repository
class AuthControllerTest extends ApiTestCase
{
    private MockObject $userRepository;
    private JwtService $jwtService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->jwtService = new JwtService($_ENV['JWT_SECRET']);

        $this->app->getContainer()->set(UserRepository::class, $this->userRepository);
    }

    public function testLoginSuccess(): void
    {
        // Arrange
        $user = new User([
            'id' => 1,
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT)
        ]);

        $this->userRepository
            ->method('findByEmail')
            ->with('test@example.com')
            ->willReturn($user);

        // Act
        $response = $this->client->post('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $data);
    }
}

### Mock d'un Service Externe
class PaymentControllerTest extends ApiTestCase
{
    private MockObject $stripeService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->stripeService = $this->createMock(StripeService::class);
        $this->app->getContainer()->set(StripeService::class, $this->stripeService);
    }

    public function testProcessPayment(): void
    {
        // Arrange
        $this->stripeService
            ->expects($this->once())
            ->method('charge')
            ->with(100, 'tok_visa')
            ->willReturn([
                'id' => 'ch_123',
                'status' => 'succeeded'
            ]);

        // Act
        $response = $this->client->post('/api/payments', [
            'amount' => 100,
            'token' => 'tok_visa'
        ]);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('succeeded', $data['status']);
    }
}

## Assertions Personnalisées

### Trait pour Tests API
trait ApiAssertions
{
    public function assertJsonStructure($response, array $structure): void
    {
        $data = json_decode($response->getContent(), true);
        
        foreach ($structure as $key => $value) {
            if (is_array($value)) {
                $this->assertArrayHasKey($key, $data);
                $this->assertJsonStructure(['data' => $value], $data[$key]);
            } else {
                $this->assertArrayHasKey($value, $data);
            }
        }
    }

    public function assertValidationError($response, string $field): void
    {
        $this->assertEquals(422, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $data);
        $this->assertArrayHasKey($field, $data['errors']);
    }
} 