<?php

namespace App\Models\Entity;

use Core\Model\BaseModel;
use Core\Exception\AuthenticationException;

class User extends BaseModel
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_PHARMACIEN = 'pharmacien';
    public const ROLE_VENDEUR = 'vendeur';

    protected static string $table = 'user';
    
    protected static array $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'created_at',
        'updated_at'
    ];

    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Vérifie si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    /**
     * Vérifie si l'utilisateur est pharmacien
     */
    public function isPharmacien(): bool
    {
        return $this->hasRole(self::ROLE_PHARMACIEN);
    }

    /**
     * Vérifie si l'utilisateur est vendeur
     */
    public function isVendeur(): bool
    {
        return $this->hasRole(self::ROLE_VENDEUR);
    }
}
