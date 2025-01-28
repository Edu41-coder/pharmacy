<?php

namespace App\Services;

use App\Models\Entity\User;
use App\Models\Repository\UserRepository;
use Core\Exception\AuthenticationException;
use Core\Session\FlashMessage;

class AuthService
{
    public const SESSION_KEY = 'user';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER = 'ROLE_USER';

    private const SESSION_LIFETIME = 3600; // 1 heure
    private const REMEMBER_ME_LIFETIME = 604800; // 1 semaine

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Authentifie un utilisateur
     */
    public function login(string $email, string $password, bool $remember = false): User
    {
        $user = $this->userRepository->authenticate($email, $password);
        
        if (!$user) {
            throw AuthenticationException::invalidCredentials();
        }

        $this->createSession($user, $remember);
        return $user;
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear user data
        unset($_SESSION[self::SESSION_KEY]);
        
        // Store flash message
        FlashMessage::success('Vous avez été déconnecté avec succès');
        
        // Regenerate session ID
        session_regenerate_id(true);
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    public function isAuthenticated(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[self::SESSION_KEY])) {
            return false;
        }

        return $this->validateSession();
    }

    /**
     * Récupère l'utilisateur connecté
     */
    public function getUser(): ?User
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        return new User($_SESSION[self::SESSION_KEY]);
    }

    /**
     * Crée la session pour l'utilisateur
     */
    private function createSession(User $user, bool $remember = false): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Regenerate session ID before setting any data
        session_regenerate_id(true);
        
        $_SESSION[self::SESSION_KEY] = $user->toArray();
        $_SESSION['last_activity'] = time();
        $_SESSION['created_at'] = time();
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['lifetime'] = $remember ? self::REMEMBER_ME_LIFETIME : self::SESSION_LIFETIME;
    }

    /**
     * Valide la session courante
     */
    private function validateSession(): bool
    {
        // Vérifier les informations de sécurité de base
        if (!isset($_SESSION['created_at']) || 
            !isset($_SESSION['ip']) || 
            !isset($_SESSION['user_agent'])) {
            return false;
        }

        // Vérifier l'IP et le User Agent
        if ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] || 
            $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            return false;
        }

        $lifetime = $_SESSION['lifetime'] ?? self::SESSION_LIFETIME;
        if ((time() - $_SESSION['last_activity']) >= $lifetime) {
            $this->handleExpiredSession();
            return false;
        }

        // Mettre à jour le timestamp de dernière activité
        $_SESSION['last_activity'] = time();
        return true;
    }

    /**
     * Gère une session expirée
     */
    private function handleExpiredSession(): void
    {
        // Sauvegarder le message flash
        FlashMessage::warning('Votre session a expiré. Veuillez vous reconnecter.');
        
        // Nettoyer la session
        unset($_SESSION[self::SESSION_KEY]);
        
        // Régénérer l'ID de session
        session_regenerate_id(true);
    }

    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     */
    public function hasRole(string $role): bool
    {
        $user = $this->getUser();
        return $user && $user->hasRole($role);
    }

    /**
     * Vérifie si l'utilisateur a l'un des rôles donnés
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }
} 