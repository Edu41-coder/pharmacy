# Design Patterns Additionnels en PHP

## Patterns Structurels Additionnels

### Bridge Pattern
Sépare une abstraction de son implémentation pour qu'elles puissent varier indépendamment.

interface DeviceImplementation {
    public function turnOn();
    public function turnOff();
}

class TV implements DeviceImplementation {
    public function turnOn() { /* ... */ }
    public function turnOff() { /* ... */ }
}

abstract class RemoteControl {
    protected DeviceImplementation $device;
    
    public function __construct(DeviceImplementation $device) {
        $this->device = $device;
    }
    
    abstract public function toggle();
}

### Composite Pattern
Permet de traiter un groupe d'objets de la même manière qu'un objet unique.

interface Component {
    public function operation(): string;
}

class Leaf implements Component {
    public function operation(): string {
        return "Leaf";
    }
}

class Composite implements Component {
    private array $children = [];
    
    public function add(Component $component): void {
        $this->children[] = $component;
    }
    
    public function operation(): string {
        $result = [];
        foreach ($this->children as $child) {
            $result[] = $child->operation();
        }
        return "Branch(" . implode("+", $result) . ")";
    }
}

### Façade Pattern
Fournit une interface unifiée à un ensemble d'interfaces dans un sous-système.

class OrderFacade {
    private ProductService $productService;
    private PaymentService $paymentService;
    private ShippingService $shippingService;
    
    public function processOrder(array $orderData): bool {
        if (!$this->productService->checkAvailability($orderData['products'])) {
            return false;
        }
        
        if (!$this->paymentService->processPayment($orderData['payment'])) {
            return false;
        }
        
        return $this->shippingService->scheduleDelivery($orderData['shipping']);
    }
}

## Patterns de Comportement Additionnels

### State Pattern
Permet à un objet de modifier son comportement quand son état interne change.

interface OrderState {
    public function processOrder(Order $order): void;
}

class NewOrderState implements OrderState {
    public function processOrder(Order $order): void {
        // Logique pour nouvel ordre
        $order->setState(new ProcessingOrderState());
    }
}

class Order {
    private OrderState $state;
    
    public function setState(OrderState $state): void {
        $this->state = $state;
    }
    
    public function process(): void {
        $this->state->processOrder($this);
    }
}

### Command Pattern
Encapsule une requête comme un objet.

interface Command {
    public function execute(): void;
}

class SaveOrderCommand implements Command {
    private Order $order;
    
    public function __construct(Order $order) {
        $this->order = $order;
    }
    
    public function execute(): void {
        $this->order->save();
    }
}

class CommandInvoker {
    private array $commands = [];
    
    public function addCommand(Command $command): void {
        $this->commands[] = $command;
    }
    
    public function executeCommands(): void {
        foreach ($this->commands as $command) {
            $command->execute();
        }
    }
}

### Iterator Pattern
Fournit un moyen d'accéder séquentiellement aux éléments d'une collection.

class ProductCollection implements \Iterator {
    private array $products = [];
    private int $position = 0;
    
    public function current(): Product {
        return $this->products[$this->position];
    }
    
    public function next(): void {
        $this->position++;
    }
    
    public function key(): int {
        return $this->position;
    }
    
    public function valid(): bool {
        return isset($this->products[$this->position]);
    }
    
    public function rewind(): void {
        $this->position = 0;
    }
}

## Architecture MVC
Le pattern MVC (Modèle-Vue-Contrôleur) est un pattern architectural qui sépare une application en trois composants principaux :

1. **Modèle** : Gestion des données et logique métier
2. **Vue** : Présentation et interface utilisateur
3. **Contrôleur** : Gestion des interactions et du flux de l'application

Exemple d'implémentation MVC :

class UserModel {
    public function getUser(int $id): array {
        // Logique d'accès aux données
        return ['id' => $id, 'name' => 'John Doe'];
    }
}

class UserController {
    private UserModel $model;
    
    public function show(int $id): string {
        $user = $this->model->getUser($id);
        return $this->render('user/show', ['user' => $user]);
    }
}

// Vue (template)
// user/show.php
<h1>Utilisateur <?= $user['name'] ?></h1>
<p>ID: <?= $user['id'] ?></p>

## Avantages des Patterns Additionnels

1. **Bridge Pattern**
   - Découple l'abstraction de l'implémentation
   - Permet l'évolution indépendante
   - Facilite l'extension des fonctionnalités

2. **Composite Pattern**
   - Simplifie le traitement des hiérarchies d'objets
   - Uniformise l'interface client
   - Facilite l'ajout de nouveaux types de composants

3. **State Pattern**
   - Encapsule les états dans des classes séparées
   - Simplifie le code des objets avec états multiples
   - Facilite l'ajout de nouveaux états

4. **Command Pattern**
   - Découple l'émetteur du récepteur
   - Permet l'annulation des opérations
   - Facilite la file d'attente des commandes

## Conclusion
Ces patterns additionnels enrichissent la boîte à outils du développeur en offrant des solutions élégantes à des problèmes de conception spécifiques. L'architecture MVC, en particulier, est devenue un standard dans le développement web moderne. 