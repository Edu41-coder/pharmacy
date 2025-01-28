<?php

namespace Core\Middleware;

use Core\Http\Request;
use Core\Http\Response;

/**
 * Interface pour les middlewares de l'application
 */
interface MiddlewareInterface
{
    /**
     * Traite la requête HTTP
     * 
     * @param Request $request La requête HTTP à traiter
     * @param callable $next Le middleware suivant dans la chaîne
     * @return Response La réponse HTTP
     * @throws MiddlewareException Si une erreur survient pendant le traitement
     */
    public function handle(Request $request, callable $next): Response;

    /**
     * Détermine si le middleware doit être exécuté
     * 
     * @param Request $request La requête HTTP
     * @return bool True si le middleware doit être exécuté
     */
    public function shouldRun(Request $request): bool;
} 