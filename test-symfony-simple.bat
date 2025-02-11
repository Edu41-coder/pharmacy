@echo off
echo === Test Simple Symfony ===

echo.
echo 1. Version de Symfony CLI
symfony -V

echo.
echo 2. Test du serveur
symfony serve --port=8000 --no-tls 