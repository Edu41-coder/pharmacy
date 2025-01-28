<?php

namespace App\Controllers\Auth;

use Core\Controller\BaseController;
use App\Models\Entity\User;
use App\Services\AuthService;
use App\Models\Repository\UserRepository;
use App\Security\CsrfToken;
use Core\Http\Request;
use Core\Http\Response;
use Core\Exception\AuthenticationException;
use Core\Exception\SecurityException;
use Core\Session\FlashMessage;

class AuthController extends BaseController
{
    private AuthService $authService;
    private UserRepository $userRepository;
    
    private const MAX_LOGIN_ATTEMPTS = 3;
    private const LOCKOUT_TIME = 900; // 15 minutes en secondes
    
    public function __construct(AuthService $authService, UserRepository $userRepository)
    {
        parent::__construct();
        $this->authService = $authService;
        $this->userRepository = $userRepository;
    }

    /**
     * Vérifie si l'utilisateur est déjà authentifié
     */
    private function checkAlreadyAuthenticated(): ?Response
    {
        if ($this->authService->isAuthenticated()) {
            $user = $this->authService->getUser();
            $redirectPath = $user->isAdmin() ? '/admin/dashboard' : '/dashboard';
            return new Response($this->redirect($redirectPath));
        }
        return null;
    }

    public function showLoginForm(): Response
    {
        if ($response = $this->checkAlreadyAuthenticated()) {
            return $response;
        }
        return new Response($this->render('auth/login', [
            'csrf_token' => CsrfToken::getToken()
        ]));
    }

    private function checkLoginAttempts(string $email): void
    {
        $attempts = $_SESSION['login_attempts'][$email] ?? 0;
        $lastAttempt = $_SESSION['last_attempt'][$email] ?? 0;

        // Réinitialiser après le délai
        if (time() - $lastAttempt > self::LOCKOUT_TIME) {
            unset($_SESSION['login_attempts'][$email]);
            unset($_SESSION['last_attempt'][$email]);
            return;
        }

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            throw new AuthenticationException(
                'Trop de tentatives. Réessayez dans 15 minutes.'
            );
        }
    }

    private function incrementLoginAttempts(string $email): void
    {
        $_SESSION['login_attempts'][$email] = ($_SESSION['login_attempts'][$email] ?? 0) + 1;
        $_SESSION['last_attempt'][$email] = time();
    }

    public function login(Request $request): Response
    {
        try {
            // Vérification du token CSRF
            if (!CsrfToken::verify($request->get('csrf_token'))) {
                throw new SecurityException('Token CSRF invalide');
            }

            $email = $request->get('email');
            
            // Vérifier les tentatives de connexion
            $this->checkLoginAttempts($email);

            // Validation des données
            if (!$request->get('email') || !$request->get('password')) {
                throw new AuthenticationException('Email et mot de passe requis');
            }

            // Validation du format email
            if (!filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
                throw new AuthenticationException('Format d\'email invalide');
            }

            $user = $this->authService->login(
                $email,
                $request->get('password')
            );

            // Réinitialiser les tentatives en cas de succès
            unset($_SESSION['login_attempts'][$email]);

            FlashMessage::success('Connexion réussie !');
            
            $redirectPath = $user->isAdmin() ? '/admin/dashboard' : '/dashboard';
            return new Response($this->redirect($redirectPath));

        } catch (SecurityException|AuthenticationException $e) {
            FlashMessage::error('Une erreur de sécurité est survenue. Veuillez réessayer.');
            return new Response($this->render('auth/login', [
                'error' => $e->getMessage(),
                'email' => $request->get('email'),
                'csrf_token' => CsrfToken::getToken()
            ]));
        }
    }

    public function logout(Request $request): Response
    {
        try {
            // Vérification du token CSRF pour le logout
            if (!CsrfToken::verify($request->get('csrf_token'))) {
                throw new SecurityException('Token CSRF invalide');
            }

            $this->authService->logout();
            
            FlashMessage::info('Vous avez été déconnecté');
            return new Response($this->redirect('/login'));

        } catch (SecurityException $e) {
            FlashMessage::error('Une erreur de sécurité est survenue.');
            return new Response($this->redirect('/dashboard'));
        }
    }

    public function showRegisterForm(): Response
    {
        if ($response = $this->checkAlreadyAuthenticated()) {
            return $response;
        }
        return new Response($this->render('auth/register', [
            'csrf_token' => CsrfToken::getToken()
        ]));
    }

    public function register(Request $request): Response
    {
        try {
            // Vérification du token CSRF
            if (!CsrfToken::verify($request->get('csrf_token'))) {
                throw new SecurityException('Token CSRF invalide');
            }

            $data = [
                'nom' => $request->get('nom'),
                'prenom' => $request->get('prenom'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'password_confirm' => $request->get('password_confirm')
            ];

            // Vérifier si l'email existe déjà
            if ($this->userRepository->findByEmail($data['email'])) {
                FlashMessage::error("Cet email est déjà utilisé");
                return new Response($this->render('auth/register', [
                    'error' => "Cet email est déjà utilisé",
                    'old' => $data,
                    'csrf_token' => CsrfToken::getToken()
                ]));
            }

            $user = $this->userRepository->register($data);
            
            if (!$user) {
                throw new \Exception("L'inscription a échoué");
            }
            
            FlashMessage::success("Inscription réussie. Vous pouvez maintenant vous connecter.");
            return new Response($this->redirect('/login'));

        } catch (SecurityException $e) {
            FlashMessage::error('Une erreur de sécurité est survenue. Veuillez réessayer.');
            return new Response($this->render('auth/register', [
                'csrf_token' => CsrfToken::getToken(),
                'old' => $data ?? []
            ]));
        } catch (\Exception $e) {
            FlashMessage::error($e->getMessage());
            return new Response($this->render('auth/register', [
                'error' => $e->getMessage(),
                'old' => $data ?? [],
                'csrf_token' => CsrfToken::getToken()
            ]));
        }
    }
} 