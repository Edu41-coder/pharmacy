# Configuration PHP pour Symfony

## Introduction

La configuration PHP est cruciale pour les performances et le bon fonctionnement d'une application Symfony. Ce document explique comment configurer PHP spécifiquement pour votre projet.

## Configuration du Projet

1. **Créer une configuration PHP spécifique**
```bash
# Copier le fichier php.ini de base
copy C:\xampp\php\php.ini php-cli.ini
```

2. **Créer le script de commande Symfony**
```batch
# symfony.bat
@echo off
php -c "%~dp0php-cli.ini" bin/console %*
```

3. **Configurer Symfony**
```yaml
# symfony.yaml
cli:
    php_ini: '%kernel.project_dir%/php-cli.ini'
```

## Structure de Configuration

```ini
# php-cli.ini
[PHP]
; Extensions essentielles
extension=php_intl.dll
extension=curl
extension=fileinfo
extension=gd
extension=mbstring
extension=exif

; Opcache
zend_extension=php_opcache.dll
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000

; Internationalisation
date.timezone=Europe/Paris
```

## Vérification

```bash
# Utiliser le script personnalisé
.\symfony.bat about

# Vérifier les indicateurs importants :
# - Intl locale: fr_FR
# - OPcache: true
# - Timezone: Europe/Paris
# - APCu: true
```

## Explications des Paramètres

### Extensions Essentielles
- **php_intl.dll** : Support internationalisation (i18n)
- **curl** : Transferts HTTP
- **fileinfo** : Information sur les fichiers
- **gd** : Manipulation d'images
- **mbstring** : Support multi-octets
- **exif** : Métadonnées images

### Cache et Performance
- **opcache** : Cache d'opcode PHP
- **memory_consumption** : Mémoire allouée au cache
- **max_accelerated_files** : Nombre max de fichiers en cache

## Bonnes Pratiques

1. **Organisation des Fichiers**
   - `php-cli.ini` : Configuration PHP spécifique au projet
   - `symfony.bat` : Script pour utiliser la configuration
   - `symfony.yaml` : Configuration Symfony

2. **Sécurité**
   - Configuration isolée par projet
   - Pas d'impact sur les autres projets XAMPP
   - Contrôle des extensions activées

3. **Performance**
   - OPcache activé
   - APCu disponible
   - Extensions optimisées

## Utilisation

Au lieu de `php bin/console`, utilisez :
```bash
.\symfony.bat [commande]

# Exemples :
.\symfony.bat about
.\symfony.bat cache:clear
.\symfony.bat make:controller
```

## Vérification Post-Installation

```bash
.\symfony.bat about

# Vérifier que :
# ✅ Intl est activé
# ✅ OPcache est activé
# ✅ Timezone est sur Europe/Paris
# ✅ APCu est activé
```

## Notes

- Les warnings sur les modules déjà chargés peuvent être ignorés
- La configuration est spécifique au projet et portable
- Le fichier php.ini principal de XAMPP reste inchangé 