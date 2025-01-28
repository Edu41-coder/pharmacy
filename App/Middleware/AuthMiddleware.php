<?php

namespace App\Middleware;

use Core\Http\Request;
use Core\Http\Response;
use Core\Middleware\MiddlewareInterface;
use App\Services\AuthService;
use Core\Exception\AuthenticationException;
use Core\Session\FlashMessage; 

class AuthMiddleware implements MiddlewareInterface
{
    private AuthService $authService;
    private array $options;

    public function __construct(AuthService $authService, array $options = [])
    {
        $this->authService = $authService;
        $this->options = array_merge([
            'redirect' => '/login',
            'roles' => [],
            'except' => []
        ], $options);
    }

    public function handle(Request $request, callable $next): Response
    {
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        if (!$this->authService->isAuthenticated()) {
            FlashMessage::warning('Vous devez être connecté pour accéder à cette page');
            return new Response($this->redirect($this->options['redirect']));
        }

        // Vérification des rôles si spécifiés
        if (!empty($this->options['roles']) && !$this->authService->hasAnyRole($this->options['roles'])) {
            throw new AuthenticationException(
                "Vous n'avez pas les permissions nécessaires pour accéder à cette page",
                403
            );
        }

        return $next($request);
    }

    public function shouldRun(Request $request): bool
    {
        return true;
    }

    private function shouldSkip(Request $request): bool
    {
        $path = $request->getUri();
        foreach ($this->options['except'] as $pattern) {
            if (preg_match($pattern, $path)) {
                return true;
            }
        }
        return false;
    }

    private function redirect(string $url): Response
    {
        return new Response($url);
    }

    public function withRoles(array $roles): self
    {
        $middleware = clone $this;
        $middleware->options['roles'] = $roles;
        return $middleware;
    }
} 