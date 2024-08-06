<?php

namespace app\Services;

use app\Models\User;
use Config\database;

class Authentification
{
    private $user;
    private $db;

    // Constructeur : initialise le modèle User et la connexion à la base de données
    public function __construct()
    {
        $this->user = new User();
        $this->db = database::getInstance()->connect();
    }

    // Gère la connexion de l'utilisateur
    public function login($email, $password)
    {
        // Recherche l'utilisateur par email
        $user = $this->user->findByEmail($email);
        // Vérifie si l'utilisateur existe et si le mot de passe est correct
        if ($user && $this->user->verifyPassword($email, $password)) {
            // Si oui, crée la session utilisateur
            $this->setUserSession($user);
            return true;
        }
        return false;
    }

    // Gère la déconnexion de l'utilisateur
    public function logout()
    {
        // Supprime toutes les variables de session
        session_unset();
        // Détruit la session
        session_destroy();
    }

    // Vérifie si un utilisateur est actuellement connecté
    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    // Récupère les informations de l'utilisateur actuellement connecté
    public function getCurrentUser()
    {
        if ($this->isLoggedIn()) {
            return $this->user->findById($_SESSION['user_id']);
        }
        return null;
    }

    // Crée la session utilisateur après une connexion réussie
    private function setUserSession($user)
    {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role_id'] = $user['role_id'];
    }

    // Gère l'inscription d'un nouvel utilisateur
    public function register($nom, $prenom, $email, $password, $role_id)
    {
        try {
            // Tente de créer un nouvel utilisateur
            $userId = $this->user->create($nom, $prenom, $email, $password, $role_id);
            if ($userId) {
                // Si la création réussit, récupère l'utilisateur créé
                $user = $this->user->findById($userId);
                // Crée la session pour le nouvel utilisateur
                $this->setUserSession($user);
                return true;
            }
        } catch (\Exception $e) {
            // En cas d'erreur, on pourrait la logger ici
            return false;
        }
        return false;
    }

    // Vérifie si l'utilisateur est connecté, sinon le redirige vers la page de connexion
    public function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
            header('Location: /login');
            exit;
        }
    }
}