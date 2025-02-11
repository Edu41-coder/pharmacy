@echo off
echo Création des dossiers de session...
mkdir var\sessions\dev 2>nul
echo Dossiers créés.

echo Configuration des permissions...
icacls var\sessions /grant Everyone:(OI)(CI)F
echo Configuration terminée.
pause 