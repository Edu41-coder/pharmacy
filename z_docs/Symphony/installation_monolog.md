# Installation de Monolog dans Symfony

## 1. Installation via Composer

```bash
composer require symfony/monolog-bundle
```

## 2. Vérification de l'installation

Le bundle devrait être automatiquement activé dans `config/bundles.php` :
```php
return [
    // ...
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
];
```

## 3. Configuration dans monolog.yaml

Une fois installé, la configuration dans `config/packages/monolog.yaml` sera active :
```yaml
monolog:
    handlers:
        main:
            type: stream
            path: "%kernel.logs_dir%/%kernel.environment%.log"
            level: debug
            channels: ["!event"]
```

## 4. Test de l'installation

Pour vérifier que tout fonctionne :
```bash
# Nettoyer le cache
.\symfony.bat cache:clear

# Vérifier la configuration
.\symfony.bat debug:config monolog
``` 