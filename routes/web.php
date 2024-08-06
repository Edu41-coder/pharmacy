<?php
// routes/web.php

// Route pour la page d'accueil
// Lorsqu'un utilisateur accède à la racine du site ("/"), le routeur appelle la méthode "index" de AuthController
$router->get('/', 'AuthController@index');

// Route pour afficher le formulaire d'inscription
// Lorsqu'un utilisateur accède à "/register" via GET, le routeur appelle la méthode "showRegisterForm" de AuthController
$router->get('/register', 'AuthController@showRegisterForm');

// Route pour traiter le formulaire d'inscription
// Lorsqu'un utilisateur soumet le formulaire d'inscription (POST vers "/register"), 
// le routeur appelle la méthode "register" de AuthController
$router->post('/register', 'AuthController@register');

// Route pour afficher le formulaire de connexion
// Lorsqu'un utilisateur accède à "/login" via GET, le routeur appelle la méthode "showLoginForm" de AuthController
$router->get('/login', 'AuthController@showLoginForm');

// Route pour traiter le formulaire de connexion
// Lorsqu'un utilisateur soumet le formulaire de connexion (POST vers "/login"), 
// le routeur appelle la méthode "login" de AuthController
$router->post('/login', 'AuthController@login');

// Route pour la déconnexion
// Lorsqu'un utilisateur accède à "/logout", le routeur appelle la méthode "logout" de AuthController
$router->get('/logout', 'AuthController@logout');