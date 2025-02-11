# Configuration des logs Symfony

## 1. Configuration Monolog

Dans `config/packages/monolog.yaml` :
```yaml
monolog:
    handlers:
        main:
            type: stream
            path: "%kernel.logs_dir%/%kernel.environment%.log"
            level: debug
            channels: ["!event"]
```

## 2. Commandes pour voir les logs

### Voir les derniers logs
```bash
type var\log\dev.log
```

### Voir les logs en temps réel (PowerShell)
```powershell
Get-Content var\log\dev.log -Wait -Tail 30
```

## 3. Nettoyer les logs

```bash
# Supprimer le fichier de log
del var\log\dev.log

# Créer un nouveau fichier vide
type nul > var\log\dev.log
```

## 4. Utiliser le Profiler Symfony

1. Ajouter `?debug=1` à l'URL :
   ```
   http://localhost:8000/login?debug=1
   ```

2. Interface du Profiler :
   - Cliquer sur la barre de débogage (en bas de page)
   - Naviguer vers la section "Logs"
   - Vérifier les entrées de sécurité et d'authentification

## 5. Logs à surveiller

- Tentatives de connexion
- Redirections
- Erreurs d'authentification
- Attribution des rôles

## 6. Astuces de débogage

- Utiliser `dump()` dans les contrôleurs
- Vérifier les sessions dans le profiler
- Surveiller les redirections dans les logs
- Examiner les tokens de sécurité 