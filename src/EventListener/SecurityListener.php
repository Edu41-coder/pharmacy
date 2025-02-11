<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Psr\Log\LoggerInterface;

class SecurityListener
{
    public function __construct(
        private Security $security,
        private UrlGeneratorInterface $urlGenerator,
        private LoggerInterface $logger
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $path = $request->getPathInfo();
        
        // Routes publiques à ignorer
        $publicPaths = [
            '/login',
            '/',
            '/logout',
            '/css',
            '/js',
            '/images',
            '/_profiler',
            '/_wdt',
        ];

        // Vérifier si le chemin commence par une des routes publiques
        foreach ($publicPaths as $publicPath) {
            if (str_starts_with($path, $publicPath)) {
                return;
            }
        }

        // Logger pour le débogage
        $this->logger->debug('Security check', [
            'path' => $path,
            'is_authenticated' => $this->security->isGranted('IS_AUTHENTICATED_FULLY'),
            'user' => $this->security->getUser()?->getUserIdentifier(),
        ]);

        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Sauvegarder l'URL demandée pour y revenir après la connexion
            $request->getSession()->set('_security.main.target_path', $request->getUri());
            
            $event->setResponse(
                new RedirectResponse(
                    $this->urlGenerator->generate('app_login')
                )
            );
        }
    }
} 