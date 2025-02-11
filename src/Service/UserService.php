<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class UserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function createUser(array $data): ?User
    {
        try {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setNom($data['nom']);
            $user->setPrenom($data['prenom']);
            $user->setRole($data['role'] ?? 'vendeur');
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $data['password'])
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $user;
        } catch (UniqueConstraintViolationException $e) {
            return null;
        }
    }

    public function updateUser(User $user, array $data): void
    {
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['nom'])) {
            $user->setNom($data['nom']);
        }
        if (isset($data['prenom'])) {
            $user->setPrenom($data['prenom']);
        }
        if (isset($data['role'])) {
            $user->setRole($data['role']);
        }
        if (isset($data['password'])) {
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $data['password'])
            );
        }

        $this->entityManager->flush();
    }

    public function deleteUser(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function findByRole(string $role): array
    {
        return $this->userRepository->findByRole($role);
    }

    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }
} 