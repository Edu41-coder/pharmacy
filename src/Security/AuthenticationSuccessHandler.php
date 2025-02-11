<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Psr\Log\LoggerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private Security $security,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $this->logger->info('Authentication success', [
            'user' => $token->getUser()->getUserIdentifier(),
            'roles' => $token->getUser()->getRoles(),
            'target_path' => $request->getSession()->get('_security.main.target_path')
        ]);

        // Vérifier s'il y a une URL de retour
        if ($targetPath = $request->getSession()->get('_security.main.target_path')) {
            $request->getSession()->remove('_security.main.target_path');
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
    }
} 