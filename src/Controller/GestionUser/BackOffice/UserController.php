<?php

namespace App\Controller\GestionUser\BackOffice;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/home_back/GestionUser', name: 'app_user_List', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('GestionUser/BackOffice/user/List.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/home_back/GestionUser/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Check if the CSRF token is valid
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_List');
    }

    #[Route('/home_back/GestionUser/Ajout', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_List');
        }

        return $this->render('GestionUser/BackOffice/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/home_back/GestionUser/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_List');
        }

        return $this->render('GestionUser/BackOffice/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

}
