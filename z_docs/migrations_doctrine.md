# Les Migrations dans Symfony/Doctrine

## Qu'est-ce qu'une migration ?

Une migration est un fichier PHP qui décrit les changements à apporter à la structure de la base de données. C'est comme un système de "versioning" pour votre base de données, similaire à Git pour votre code.

## Processus de Migration

### 1. Création de la Migration

```bash
php bin/console make:migration
```

Cette commande :
1. Compare l'état actuel de la base de données avec vos entités Doctrine
2. Génère un fichier PHP dans `migrations/` avec les différences
3. Ce fichier contient deux méthodes :
   - `up()` : Applique les changements
   - `down()` : Annule les changements (rollback)

### 2. Structure d'une Migration

```php
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250203154546 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // Crée les tables, colonnes, etc.
        $this->addSql('CREATE TABLE user (...)');
    }

    public function down(Schema $schema): void
    {
        // Annule les changements
        $this->addSql('DROP TABLE user');
    }
}
```

### 3. Exécution de la Migration

```bash
php bin/console doctrine:migrations:migrate
```

## Comment ça marche ?

1. **Détection des Changements**
   - Doctrine lit vos entités PHP (ex: User.php)
   - Compare avec la base de données existante
   - Identifie les différences (nouvelles tables, colonnes, etc.)

2. **Génération du SQL**
   - Convertit les annotations/attributs PHP en SQL
   - Gère les types de données (ex: string → VARCHAR)
   - Ajoute les contraintes (clés primaires, foreign keys)

3. **Suivi des Versions**
   - Crée une table `doctrine_migration_versions`
   - Enregistre chaque migration exécutée
   - Permet de savoir où on en est

## Exemple Concret

### 1. Entité PHP
```php
#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;
}
```

### 2. Migration Générée
```php
public function up(Schema $schema): void
{
    $this->addSql('CREATE TABLE user (
        id INT AUTO_INCREMENT NOT NULL,
        email VARCHAR(180) NOT NULL,
        PRIMARY KEY(id)
    )');
}
```

## Avantages des Migrations

1. **Versioning**
   - Historique des changements
   - Possibilité de rollback
   - Déploiement contrôlé

2. **Collaboration**
   - Partage des changements entre développeurs
   - Synchronisation des bases de données
   - Intégration continue

3. **Sécurité**
   - Tests avant application
   - Backup automatique possible
   - Validation des changements

## Bonnes Pratiques

1. **Vérifier les Migrations**
   - Relire le fichier généré
   - Tester sur un environnement de dev
   - Faire des backups

2. **Versioning**
   - Commiter les migrations avec le code
   - Ne pas modifier une migration déjà exécutée
   - Créer une nouvelle migration pour les corrections

3. **Déploiement**
   - Tester la migration complète
   - Prévoir la procédure de rollback
   - Documenter les changements majeurs 