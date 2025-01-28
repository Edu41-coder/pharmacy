<?php

namespace App\Controllers\Admin;

use Core\Controller\BaseController;
use App\Models\Entity\User;
use App\Services\AuthService;
use App\Models\Repository\UserRepository;
use Core\Http\Request;
use Core\Http\Response;
use Core\Exception\AuthenticationException;
use Core\Session\FlashMessage;

// Gère la création/modification des utilisateurs par l'admin
class UserController extends BaseController
{
    private AuthService $authService;
    private UserRepository $userRepository;

    public function __construct(AuthService $authService, UserRepository $userRepository)
    {
        parent::__construct();
        $this->authService = $authService;
        $this->userRepository = $userRepository;
        $this->checkIsAdmin();
    }

    /**
     * Liste tous les utilisateurs
     */
    public function index(): Response
    {
        $users = User::findAll();
        return new Response($this->render('admin/users/index', [
            'users' => $users
        ]));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create(): Response
    {
        return new Response($this->render('admin/users/create'));
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function store(Request $request): Response
    {
        try {
            $data = [
                'nom' => $request->get('nom'),
                'prenom' => $request->get('prenom'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'role' => $request->get('role')
            ];

            if ($this->userRepository->findByEmail($data['email'])) {
                FlashMessage::error("Cet email est déjà utilisé");
                return new Response($this->render('admin/users/create', [
                    'error' => "Cet email est déjà utilisé",
                    'old' => $data
                ]));
            }

            $user = $this->userRepository->register($data);
            
            if (!$user) {
                throw new \Exception("L'enregistrement a échoué");
            }

            FlashMessage::success("Utilisateur créé avec succès");
            return new Response($this->redirect('/admin/users'));

        } catch (\Exception $e) {
            FlashMessage::error($e->getMessage());
            return new Response($this->render('admin/users/create', [
                'error' => $e->getMessage(),
                'old' => $data ?? []
            ]));
        }
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(int $id): Response
    {
        $user = User::findById($id);
        
        if (!$user) {
            FlashMessage::error('Utilisateur non trouvé');
            return new Response($this->redirect('/admin/users'));
        }

        return new Response($this->render('admin/users/edit', [
            'user' => $user
        ]));
    }

    /**
     * Met à jour un utilisateur
     */
    public function update(Request $request, int $id): Response
    {
        try {
            $user = User::findById($id);
            if (!$user) {
                throw new \Exception('Utilisateur non trouvé');
            }

            $data = [
                'nom' => $request->get('nom'),
                'prenom' => $request->get('prenom'),
                'email' => $request->get('email'),
                'role' => $request->get('role')
            ];

            if ($password = $request->get('password')) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $user->update($id, $data);
            
            FlashMessage::success("Utilisateur mis à jour avec succès");
            return new Response($this->redirect('/admin/users'));

        } catch (\Exception $e) {
            FlashMessage::error($e->getMessage());
            return new Response($this->render('admin/users/edit', [
                'user' => $user ?? null,
                'error' => $e->getMessage()
            ]));
        }
    }

    /**
     * Supprime un utilisateur
     */
    public function delete(Request $request, int $id): Response
    {
        try {
            $user = User::findById($id);
            if (!$user) {
                throw new \Exception('Utilisateur non trouvé');
            }

            $user->delete($id);
            
            FlashMessage::success("Utilisateur supprimé avec succès");
            return new Response($this->redirect('/admin/users'));

        } catch (\Exception $e) {
            FlashMessage::error($e->getMessage());
            return new Response($this->redirect('/admin/users'));
        }
    }

    /**
     * Vérifie que l'utilisateur est admin
     */
    private function checkIsAdmin(): void
    {
        if (!$this->authService->isAuthenticated() || !$this->authService->getUser()->isAdmin()) {
            throw new AuthenticationException("Accès non autorisé", 403);
        }
    }
} 