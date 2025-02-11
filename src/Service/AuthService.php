<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private Security $security
    ) {}

    public function register(array $data): User
    {
        // Vérifier si l'email existe déjà
        if ($this->userRepository->findByEmail($data['email'])) {
            throw new AuthenticationException('Cet email est déjà utilisé');
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom']);
        $user->setRole($data['role'] ?? 'vendeur');
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $data['password'])
        );
        
        $this->userRepository->save($user, true);
        return $user;
    }

    public function getCurrentUser(): ?User
    {
        $user = $this->security->getUser();
        return $user instanceof User ? $user : null;
    }

    public function isAuthenticated(): bool
    {
        return $this->security->isGranted('IS_AUTHENTICATED_FULLY');
    }

    public function hasRole(string $role): bool
    {
        return $this->security->isGranted('ROLE_' . strtoupper($role));
    }

    public function validatePassword(User $user, string $password): bool
    {
        return $this->passwordHasher->isPasswordValid($user, $password);
    }

    public function updatePassword(User $user, string $newPassword): void
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $newPassword)
        );
        $this->userRepository->save($user, true);
    }
} 