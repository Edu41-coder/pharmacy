@echo off
echo === Démarrage Serveur Symfony ===

REM Nettoyage du port 8000
for /f "tokens=5" %%a in ('netstat -ano ^| findstr :8000') do (
    taskkill /F /PID %%a 2>nul
)

echo.
echo Version de Symfony :
symfony -V

echo.
echo Démarrage du serveur...
cd C:\xampp\htdocs\Pharmacie
symfony serve --port=8000 --no-tls

REM Vérifier les exigences
echo Vérification des exigences :
symfony check:req

timeout /t 2

REM Démarrage avec debug
echo Démarrage du serveur Symfony...
symfony serve --port=8000 --no-tls -v 