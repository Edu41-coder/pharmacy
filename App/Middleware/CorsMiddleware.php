<?php

namespace App\Middleware;

use Core\Middleware\MiddlewareInterface;
use Core\Http\Request;
use Core\Http\Response;
use Core\Http\JsonResponse;

class CorsMiddleware implements MiddlewareInterface
{
    /**
     * En-têtes autorisés par défaut
     */
    private const ALLOWED_HEADERS = [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'Accept',
        'Origin',
        'X-CSRF-TOKEN'
    ];

    /**
     * Méthodes HTTP autorisées
     */
    private const ALLOWED_METHODS = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'OPTIONS'
    ];

    /**
     * Durée de cache des requêtes preflight (1 heure)
     */
    private const MAX_AGE = 3600;

    /**
     * Gère les en-têtes CORS
     */
    public function handle(Request $request, callable $next): Response
    {
        // Démarrer la session si nécessaire
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Configurer les en-têtes CORS
        $this->setCorsHeaders();
        
        // Configurer les en-têtes de sécurité
        $this->setSecurityHeaders();

        // Gérer les requêtes OPTIONS (preflight)
        if ($request->getMethod() === 'OPTIONS') {
            return new JsonResponse(null, 204);
        }

        // Continuer le traitement
        $response = $next($request);

        // Réappliquer les en-têtes CORS à la réponse
        $this->setCorsHeaders();

        return $response;
    }

    /**
     * Configure les en-têtes CORS
     */
    private function setCorsHeaders(): void
    {
        // Autoriser l'origine
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
        header('Access-Control-Allow-Origin: ' . $origin);
        
        // Autoriser les credentials
        header('Access-Control-Allow-Credentials: true');
        
        // Méthodes autorisées
        header('Access-Control-Allow-Methods: ' . implode(', ', self::ALLOWED_METHODS));
        
        // En-têtes autorisés
        header('Access-Control-Allow-Headers: ' . implode(', ', self::ALLOWED_HEADERS));
        
        // Durée de cache
        header('Access-Control-Max-Age: ' . self::MAX_AGE);
        
        // Cookie sécurisé
        header('Set-Cookie: PHPSESSID=' . session_id() . '; SameSite=Lax; Path=/; HttpOnly');
        
        // Vary
        header('Vary: Origin');
    }

    /**
     * Configure les en-têtes de sécurité
     */
    private function setSecurityHeaders(): void
    {
        // Protection XSS
        header('X-XSS-Protection: 1; mode=block');
        
        // Protection contre le MIME-sniffing
        header('X-Content-Type-Options: nosniff');
        
        // Politique de référencement
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Content Security Policy de base
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline'; " .
               "style-src 'self' 'unsafe-inline'; " .
               "img-src 'self' data:; " .
               "connect-src 'self';";
        
        header("Content-Security-Policy: " . $csp);
    }

    public function shouldRun(Request $request): bool
    {
        return true;
    }
} 