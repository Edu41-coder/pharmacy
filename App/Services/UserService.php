<?php

namespace App\Services;

use App\Models\Entity\User;
use App\Models\Repository\UserRepository;
use Core\Database\Database;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(Database $db)
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * Récupère un utilisateur par son ID
     */
    public function getUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Récupère un utilisateur par son email
     */
    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function createUser(array $data): ?User
    {
        return $this->userRepository->register($data);
    }

    /**
     * Met à jour un utilisateur
     */
    public function updateUser(int $id, array $data): bool
    {
        return $this->userRepository->update($id, $data);
    }

    /**
     * Supprime un utilisateur
     */
    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }
} 