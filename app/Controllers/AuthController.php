<?php

namespace app\Controllers;

use app\Services\Authentification;
use app\Models\User;

class AuthController
{
    private $auth;
    private $user;

    // Constructeur : initialise les services d'authentification et le modèle utilisateur
    public function __construct()
    {
        $this->auth = new Authentification();
        $this->user = new User();
    }

    // Affiche le formulaire d'inscription
    public function showRegisterForm()
    {
        require_once __DIR__ . '/../Views/register.php';
    }

    // Traite la soumission du formulaire d'inscription
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère les données du formulaire
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role_id = 2; // Rôle par défaut pour les nouveaux utilisateurs

            try {
                // Tente de créer l'utilisateur
                $userId = $this->user->create($nom, $prenom, $email, $password, $role_id);
                if ($userId) {
                    // Si réussi, redirige vers la page de connexion avec un message de succès
                    $_SESSION['success'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                    header('Location: /login');
                    exit;
                }
            } catch (\Exception $e) {
                // En cas d'erreur, stocke le message d'erreur dans la session
                $_SESSION['error'] = $e->getMessage();
            }
        }

        // Si on arrive ici, c'est qu'il y a eu une erreur, redirige vers le formulaire d'inscription
        header('Location: /register');
        exit;
    }

    // Affiche le formulaire de connexion
    public function showLoginForm()
    {
        require_once __DIR__ . '/../Views/login.php';
    }

    // Traite la soumission du formulaire de connexion
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Tente de connecter l'utilisateur
            if ($this->auth->login($email, $password)) {
                // Si réussi, redirige vers la page d'accueil avec un message de succès
                $_SESSION['success'] = "Connexion réussie.";
                header('Location: /');
                exit;
            } else {
                // Si échec, redirige vers la page de connexion avec un message d'erreur
                $_SESSION['error'] = "Email ou mot de passe incorrect.";
                header('Location: /login');
                exit;
            }
        }

        // Si on arrive ici, c'est qu'il y a eu une erreur, redirige vers le formulaire de connexion
        header('Location: /login');
        exit;
    }

    // Gère la déconnexion de l'utilisateur
    public function logout()
    {
        $this->auth->logout();
        $_SESSION['success'] = "Vous avez été déconnecté avec succès.";
        header('Location: /');
        exit;
    }

    // Affiche la page d'accueil
    public function index()
    {
        $isLoggedIn = false;

        // Vérifier si l'utilisateur est connecté
        if (isset($_SESSION['user_id'])) {
            $isLoggedIn = true;
            // Vous pouvez également récupérer les informations de l'utilisateur ici si nécessaire
            $user = $this->auth->getCurrentUser();
        }

        // Inclure la vue en passant les variables nécessaires
        require_once dirname(__DIR__) . '/Views/index.php';
    }

    // Méthode utilitaire pour vérifier si l'utilisateur est connecté
    // Si non, redirige vers la page de connexion
    private function requireLogin()
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
            header('Location: /login');
            exit;
        }
    }
}