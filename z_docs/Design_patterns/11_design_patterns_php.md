# Design Patterns en PHP

## Introduction
Les design patterns sont des solutions réutilisables aux problèmes couramment rencontrés dans la conception de logiciels. Ils fournissent un modèle pour résoudre les problèmes de conception de manière élégante et réutilisable.

## Avantages
- Réutilisation de code éprouvé
- Meilleure maintenabilité
- Code plus structuré et organisé
- Communication facilitée entre développeurs
- Solutions standardisées aux problèmes communs

## Types Principaux

### 1. Patterns Créationnels
Concernent la création d'objets.

#### Factory Method
Définit une interface pour créer un objet, mais laisse les sous-classes décider quelle classe instancier.

Exemple de Factory Method :

interface Animal {
    public function makeSound(): string;
}

class Dog implements Animal {
    public function makeSound(): string {
        return "Woof!";
    }
}

class Cat implements Animal {
    public function makeSound(): string {
        return "Meow!";
    }
}

class AnimalFactory {
    public function createAnimal(string $type): Animal {
        return match ($type) {
            'dog' => new Dog(),
            'cat' => new Cat(),
            default => throw new \InvalidArgumentException("Animal type non valide")
        };
    }
}

#### Singleton
Garantit qu'une classe n'a qu'une seule instance et fournit un point d'accès global à celle-ci.

class Database {
    private static ?self $instance = null;
    private function __construct() {} // Constructeur privé

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

### 2. Patterns Structurels
Concernent la composition des classes et des objets.

#### Adapter
Permet à des interfaces incompatibles de travailler ensemble.

interface PaymentProcessor {
    public function processPayment(float $amount): bool;
}

class PayPalAPI {
    public function sendPayment(float $amount, string $currency): string {
        return "Payment processed";
    }
}

class PayPalAdapter implements PaymentProcessor {
    private PayPalAPI $paypal;

    public function __construct(PayPalAPI $paypal) {
        $this->paypal = $paypal;
    }

    public function processPayment(float $amount): bool {
        $result = $this->paypal->sendPayment($amount, 'EUR');
        return $result === "Payment processed";
    }
}

### 3. Patterns Comportementaux
Concernent la communication entre les objets.

#### Observer
Définit une dépendance un-à-plusieurs entre objets.

interface Observer {
    public function update(string $message): void;
}

class Newsletter {
    private array $observers = [];
    
    public function attach(Observer $observer): void {
        $this->observers[] = $observer;
    }
    
    public function notify(string $message): void {
        foreach ($this->observers as $observer) {
            $observer->update($message);
        }
    }
}

class Subscriber implements Observer {
    private string $email;
    
    public function __construct(string $email) {
        $this->email = $email;
    }
    
    public function update(string $message): void {
        echo "Envoi à {$this->email}: {$message}";
    }
}

## Patterns Couramment Utilisés

1. **Repository Pattern**
   - Abstrait la couche de données
   - Centralise la logique d'accès aux données
   - Facilite les tests et la maintenance

2. **Dependency Injection**
   - Réduit le couplage entre les classes
   - Améliore la testabilité
   - Facilite la modification des dépendances

3. **Strategy Pattern**
   - Permet de changer l'algorithme utilisé à l'exécution
   - Encapsule les algorithmes dans des classes séparées
   - Rend le code plus flexible

4. **Decorator Pattern**
   - Ajoute des fonctionnalités à un objet dynamiquement
   - Alternative à l'héritage
   - Respecte le principe Open/Closed

## Bonnes Pratiques

1. **Ne pas surcharger**
   - Utiliser les patterns uniquement quand nécessaire
   - Éviter la sur-ingénierie
   - Garder le code simple quand possible

2. **Documentation**
   - Documenter l'utilisation des patterns
   - Expliquer les choix de conception
   - Maintenir une documentation à jour

3. **Tests**
   - Tester chaque pattern implémenté
   - Vérifier les cas limites
   - S'assurer de la robustesse du code

## Conclusion
Les design patterns sont des outils puissants pour améliorer la qualité du code, mais doivent être utilisés avec discernement. Ils ne sont pas une solution miracle et leur utilisation doit être justifiée par un besoin réel. 