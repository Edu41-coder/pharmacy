@echo off
echo Nettoyage en cours...

REM Supprimer le cache
rd /s /q var\cache
mkdir var\cache

REM Vider les logs
type nul > var\log\dev.log

REM Nettoyer la base de données SQLite si nécessaire
del var\data.db 2>nul

echo Nettoyage terminé.
pause 