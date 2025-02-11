<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;

#[Route('/admin/utilisateurs')]
#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private FormFactoryInterface $formFactory
    ) {}

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'form.factory' => '?'.FormFactoryInterface::class,
        ]);
    }

    #[Route('/', name: 'admin_users_index')]
    public function index(): Response
    {
        return $this->render('admin/utilisateurs/index.html.twig', [
            'users' => $this->userService->findAll()
        ]);
    }

    #[Route('/new', name: 'admin_users_new')]
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->formFactory->create(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUser = $this->userService->createUser([
                'email' => $form->get('email')->getData(),
                'nom' => $form->get('nom')->getData(),
                'prenom' => $form->get('prenom')->getData(),
                'role' => $form->get('role')->getData(),
                'password' => $form->get('plainPassword')->getData()
            ]);

            if ($newUser === null) {
                $this->addFlash('error', 'Cette adresse email est déjà utilisée.');
            } else {
                $this->addFlash('success', 'Utilisateur créé avec succès');
                return $this->redirectToRoute('admin_users_index');
            }
        }

        return $this->render('admin/utilisateurs/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_users_edit')]
    public function edit(Request $request, User $user): Response
    {
        if ($user === $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas modifier votre propre compte');
            return $this->redirectToRoute('admin_users_index');
        }

        $form = $this->formFactory->create(UserType::class, $user, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->updateUser($user, [
                'email' => $form->get('email')->getData(),
                'nom' => $form->get('nom')->getData(),
                'prenom' => $form->get('prenom')->getData(),
                'role' => $form->get('role')->getData(),
                'password' => $form->get('plainPassword')->getData()
            ]);
            
            $this->addFlash('success', 'Utilisateur mis à jour');
            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/utilisateurs/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_users_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide');
        }

        if ($user === $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte');
            return $this->redirectToRoute('admin_users_index');
        }

        $this->userService->deleteUser($user);

        $this->addFlash('success', 'Utilisateur supprimé avec succès');
        return $this->redirectToRoute('admin_users_index');
    }
} 