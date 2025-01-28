<?php

namespace App\Security;

use Core\Exception\SecurityException;

class CsrfToken
{
    private const TOKEN_LIFETIME = 3600; // 1 heure

    /**
     * Génère un nouveau token CSRF
     */
    public static function generate(): string
    {
        self::initSession();

        try {
            $token = bin2hex(random_bytes(32));
        } catch (\Exception $e) {
            throw SecurityException::invalidCsrfToken('Impossible de générer un token CSRF sécurisé');
        }

        $_SESSION['csrf_token'] = [
            'token' => $token,
            'time' => time()
        ];

        return $token;
    }

    /**
     * Récupère le token CSRF actuel ou en génère un nouveau
     */
    public static function getToken(): string
    {
        self::initSession();

        if (isset($_SESSION['csrf_token'])) {
            $stored = $_SESSION['csrf_token'];
            if (time() - $stored['time'] < self::TOKEN_LIFETIME) {
                return $stored['token'];
            }
        }

        return self::generate();
    }

    /**
     * Vérifie si le token fourni est valide
     */
    public static function verify(?string $token): bool
    {
        self::initSession();

        if (!$token || !isset($_SESSION['csrf_token'])) {
            return false;
        }

        $stored = $_SESSION['csrf_token'];
        
        if (time() - $stored['time'] >= self::TOKEN_LIFETIME) {
            return false;
        }

        return hash_equals($stored['token'], $token);
    }

    /**
     * Génère un champ HTML caché avec le token CSRF
     */
    public static function getHiddenInput(): string
    {
        $token = self::getToken();
        return sprintf(
            '<input type="hidden" name="csrf_token" value="%s">',
            htmlspecialchars($token, ENT_QUOTES, 'UTF-8')
        );
    }

    /**
     * Initialise la session si nécessaire
     */
    private static function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            if (headers_sent()) {
                throw SecurityException::sessionError('Impossible de démarrer la session : les en-têtes ont déjà été envoyés');
            }
            session_start();
        }
    }
} 