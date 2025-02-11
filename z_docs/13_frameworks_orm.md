# Symfony, Doctrine et Laravel : Comprendre les Frameworks PHP

## Introduction
Les frameworks PHP modernes et les ORM (Object-Relational Mapping) sont des outils essentiels pour le développement d'applications web. Comprendre leurs rôles et interactions est crucial pour le développement efficace.

## Symfony
Framework PHP complet et modulaire.

### Caractéristiques principales
1. **Composants réutilisables**
   - Routing
   - Formulaires
   - Sécurité
   - Cache

2. **Architecture**
   - MVC (Modèle-Vue-Contrôleur)
   - Dependency Injection
   - Event Dispatcher

3. **Flexibilité**
   - Utilisable en totalité ou par composants
   - Intégrable dans d'autres projets
   - Hautement configurable

## Doctrine
ORM (Object-Relational Mapping) officiel de Symfony.

### Fonctionnalités
1. **Mapping Objet-Relationnel**
   - Transforme les tables en classes PHP
   - Gère les relations entre entités
   - Abstrait la base de données

2. **Types de Mapping**
   - Annotations/Attributs PHP 8
   - XML
   - YAML

3. **Exemple de Mapping**
   ```php
   #[ORM\Entity]
   class User
   {
       #[ORM\Id]
       #[ORM\GeneratedValue]
       #[ORM\Column(type: 'integer')]
       private $id;

       #[ORM\Column(type: 'string')]
       private $name;
   }
   ```

### Avantages
- Abstraction de la base de données
- Code plus maintenable
- Migrations automatiques
- Requêtes orientées objet

## Laravel
Framework PHP alternatif à Symfony.

### Caractéristiques
1. **Eloquent ORM**
   - ORM intégré à Laravel
   - Syntaxe plus simple que Doctrine
   - Active Record Pattern

2. **Artisan CLI**
   - Générateur de code
   - Gestion des migrations
   - Commandes personnalisées

3. **Exemple Eloquent**
   ```php
   class User extends Model
   {
       protected $fillable = ['name', 'email'];
       
       public function posts()
       {
           return $this->hasMany(Post::class);
       }
   }
   ```

## Comparaison des Approches

### Doctrine vs Eloquent
1. **Doctrine (Symfony)**
   - Plus verbeux mais plus flexible
   - Mapping explicite
   - Data Mapper Pattern
   - Meilleure pour projets complexes

2. **Eloquent (Laravel)**
   - Plus simple à apprendre
   - Convention over Configuration
   - Active Record Pattern
   - Idéal pour projets moyens

### Utilisation dans le Projet

1. **Avec Symfony/Doctrine**
   ```php
   // Configuration
   $entityManager = EntityManager::create($connection, $config);

   // Utilisation
   $user = new User();
   $user->setName('John');
   
   $entityManager->persist($user);
   $entityManager->flush();
   ```

2. **Avec Laravel/Eloquent**
   ```php
   // Création
   $user = User::create([
       'name' => 'John',
       'email' => 'john@example.com'
   ]);

   // Requête
   $users = User::where('active', true)->get();
   ```

## Interaction entre les Composants

### Dans Symfony
1. **Controller → Doctrine → Base de données**
   ```php
   class UserController
   {
       public function index(EntityManagerInterface $em)
       {
           $users = $em->getRepository(User::class)->findAll();
           return $this->render('users/index.html.twig', [
               'users' => $users
           ]);
       }
   }
   ```

2. **Service → Doctrine → Entité**
   ```php
   class UserService
   {
       private $em;

       public function __construct(EntityManagerInterface $em)
       {
           $this->em = $em;
       }

       public function createUser(array $data): User
       {
           $user = new User();
           $user->setName($data['name']);
           
           $this->em->persist($user);
           $this->em->flush();
           
           return $user;
       }
   }
   ```

### Dans Laravel
1. **Controller → Eloquent → Base de données**
   ```php
   class UserController extends Controller
   {
       public function index()
       {
           $users = User::all();
           return view('users.index', compact('users'));
       }
   }
   ```

## Conclusion
- Symfony avec Doctrine offre plus de contrôle et de flexibilité
- Laravel avec Eloquent privilégie la simplicité et la rapidité de développement
- Le choix dépend des besoins du projet :
  * Projet complexe → Symfony/Doctrine
  * Projet moyen/simple → Laravel/Eloquent
  * Besoin de composants spécifiques → Possible de mixer les approches 