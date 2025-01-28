<?php

namespace App\Models\Repository;

use App\Models\Entity\User;
use Core\Database\Database;

class UserRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Trouve un utilisateur par son ID
     */
    public function findById(int $id): ?User
    {
        $data = $this->db->fetchOne(
            "SELECT * FROM user WHERE id = :id",
            ['id' => $id]
        );

        return $data ? new User($data) : null;
    }

    /**
     * Trouve un utilisateur par son email
     */
    public function findByEmail(string $email): ?User
    {
        $data = $this->db->fetchOne(
            "SELECT * FROM user WHERE email = :email",
            ['email' => $email]
        );

        return $data ? new User($data) : null;
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function create(array $data): ?User
    {
        // Vérifier si l'email existe déjà
        if ($this->findByEmail($data['email'])) {
            return null;
        }

        $userId = $this->db->insert('user', $data);
        
        return $userId ? $this->findById($userId) : null;
    }

    /**
     * Met à jour un utilisateur
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE user SET " . 
            implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data))) .
            " WHERE id = :id";
        
        return $this->db->query($sql, array_merge($data, ['id' => $id]))->rowCount() > 0;
    }

    /**
     * Supprime un utilisateur
     */
    public function delete(int $id): bool
    {
        return $this->db->query(
            "DELETE FROM user WHERE id = :id",
            ['id' => $id]
        )->rowCount() > 0;
    }

    /**
     * Authentifie un utilisateur
     */
    public function authenticate(string $email, string $password): ?User
    {
        $user = $this->findByEmail($email);
        
        if (!$user || !password_verify($password, $user->password)) {
            return null;
        }

        return $user;
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function register(array $data): ?User
    {
        // Hasher le mot de passe
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        return $this->create($data);
    }
} 