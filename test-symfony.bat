@echo off
echo Test des commandes Symfony...

echo.
echo === Version de Symfony ===
symfony -V

echo.
echo === Liste des commandes ===
symfony list

echo.
echo === Status du serveur ===
symfony server:status

echo.
echo === Localisation de Symfony ===
where symfony

echo.
echo === Variable PATH ===
echo %PATH%

echo.
echo === Test de PHP ===
php -v

echo.
pause 