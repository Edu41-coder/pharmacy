<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class FlashService
{
    private Session $session;

    public function __construct(
        private RequestStack $requestStack
    ) {
        $this->session = $requestStack->getSession();
    }

    private function addFlash(string $type, string $message): void
    {
        $this->session->getFlashBag()->add($type, $message);
    }

    public function success(string $message): void
    {
        $this->addFlash('success', $message);
    }

    public function error(string $message): void
    {
        $this->addFlash('error', $message);
    }

    public function warning(string $message): void
    {
        $this->addFlash('warning', $message);
    }

    public function info(string $message): void
    {
        $this->addFlash('info', $message);
    }

    /**
     * Récupère tous les messages flash d'un type donné
     */
    public function get(string $type): array
    {
        return $this->session->getFlashBag()->all()[$type] ?? [];
    }

    /**
     * Vérifie si des messages flash existent
     */
    public function has(string $type): bool
    {
        return !empty($this->session->getFlashBag()->peek($type));
    }
}