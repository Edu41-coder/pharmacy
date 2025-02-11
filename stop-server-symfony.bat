@echo off
echo === Arrêt Serveur Symfony ===

echo.
echo Arrêt du serveur...
"%USERPROFILE%\Symfony\symfony.exe" server:stop

REM Nettoyage supplémentaire
taskkill /F /IM php.exe 2>nul
taskkill /F /IM php-cgi.exe 2>nul

echo.
echo Serveur arrêté.
pause 