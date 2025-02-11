@echo off
echo Arrêt du serveur PHP...

REM Nettoyage
taskkill /F /IM php.exe 2>nul
taskkill /F /IM php-cgi.exe 2>nul

timeout /t 2
echo Serveur arrêté. 