@echo off
echo Arrêt des processus existants...

REM Nettoyage initial
taskkill /F /IM php.exe 2>nul
taskkill /F /IM php-cgi.exe 2>nul

REM Nettoyage du port 8000
for /f "tokens=5" %%a in ('netstat -ano ^| findstr :8000') do (
    taskkill /F /PID %%a 2>nul
)

timeout /t 2
echo Démarrage du serveur PHP...

REM Démarrage du serveur PHP
cd public
php -S 127.0.0.1:8000 