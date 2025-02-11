# Configuration PHP pour Symfony

## Introduction

La configuration PHP est cruciale pour les performances et le bon fonctionnement d'une application Symfony. Ce document explique comment configurer PHP spécifiquement pour votre projet.

## Création du fichier de configuration

1. **Créer un fichier PHP.ini local**
```bash
# Copier le fichier php.ini existant
copy C:\xampp\php\php.ini .\.php.ini
```

2. **Structure du fichier**
```ini
[PHP]
; ====================================
;           Extensions
; ====================================
extension=intl
extension=gd
extension=zip
extension=fileinfo
extension=exif
extension=mbstring

; ====================================
;       Accélérateur PHP (OPcache)
; ====================================
zend_extension=opcache
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000

; ====================================
;           Cache système
; ====================================
realpath_cache_size=5M
realpath_cache_ttl=600

; ====================================
;       Limites et Performances
; ====================================
; Upload
upload_max_filesize=10M
post_max_size=12M

; Mémoire
memory_limit=256M

; Timeouts
max_execution_time=60
max_input_time=60

; ====================================
;           Développement
; ====================================
display_errors=On
display_startup_errors=On
error_reporting=E_ALL

; ====================================
;         Internationalisation
; ====================================
date.timezone=Europe/Paris
```

## Configuration Symfony

1. **Créer/Modifier symfony.yaml**
```yaml
cli:
    php_ini: .php.ini
```

## Vérification de la configuration

```bash
# Vérifier le fichier php.ini utilisé
php -i | findstr "Loaded Configuration File"

# Vérifier la configuration Symfony
php bin/console about
```

## Explications des paramètres

### Extensions
- **intl** : Support internationalisation
- **gd** : Manipulation d'images
- **zip** : Compression/décompression
- **fileinfo** : Information sur les fichiers
- **exif** : Métadonnées images
- **mbstring** : Support multi-octets

### OPcache
- **enable** : Active l'accélérateur
- **memory_consumption** : Mémoire allouée
- **max_accelerated_files** : Nombre max de fichiers en cache

### Performances
- **realpath_cache** : Cache des chemins de fichiers
- **memory_limit** : Limite mémoire PHP
- **max_execution_time** : Temps max d'exécution

### Upload
- **upload_max_filesize** : Taille max fichier
- **post_max_size** : Taille max requête POST

## Bonnes pratiques

1. **Environnement spécifique**
   - Développement : Erreurs activées
   - Production : Erreurs désactivées

2. **Sécurité**
   - Limiter les uploads
   - Configurer les timeouts
   - Désactiver les fonctions dangereuses

3. **Performance**
   - Activer OPcache
   - Ajuster la mémoire selon les besoins
   - Optimiser le cache

## Redémarrage des services

Après modification :
```bash
# Windows (XAMPP)
net stop apache2.4
net start apache2.4
``` 