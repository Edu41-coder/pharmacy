<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Psr\Log\LoggerInterface;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    #[Route('/', name: 'app_home')]
    #[IsGranted('ROLE_USER')]
    public function index(LoggerInterface $logger): Response
    {
        $user = $this->getUser();
        
        $logger->info('Dashboard accessed', [
            'user' => $user->getUserIdentifier(),
            'roles' => $user->getRoles()
        ]);

        return $this->render('dashboard/index.html.twig', [
            'user' => $user
        ]);
    }
} 