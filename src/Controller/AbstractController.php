<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractController extends SymfonyAbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {}

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        // Ajouter les données partagées
        $viewData = array_merge([
            'site_title' => $this->getParameter('app.site_title'),
            'user' => $this->getUser(),
        ], $parameters);

        return parent::render($view . '.html.twig', $viewData, $response);
    }

    protected function isAuthenticated(): bool
    {
        return $this->isGranted('IS_AUTHENTICATED_FULLY');
    }

    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            throw new AccessDeniedException('Vous devez être connecté.');
        }
    }

    protected function requireAdmin(): void
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Accès réservé aux administrateurs.');
        }
    }
} 