<?php

namespace App\Controller;

use App\Entity\Bulletinpaie;
use App\Form\BulletinpaieType;
use App\Repository\BulletinpaieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/bulletinpaie')]
final class BulletinpaieController extends AbstractController
{
    #[Route(name: 'app_bulletinpaie_index', methods: ['GET'])]
    public function index(BulletinpaieRepository $bulletinpaieRepository): Response
    {
        return $this->render('bulletinpaie/index.html.twig', [
            'bulletinpaies' => $bulletinpaieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bulletinpaie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bulletinpaie = new Bulletinpaie();
        $form = $this->createForm(BulletinpaieType::class, $bulletinpaie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $entityManager->persist($bulletinpaie);
            $entityManager->flush();

            return $this->redirectToRoute('app_bulletinpaie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bulletinpaie/new.html.twig', [
            'bulletinpaie' => $bulletinpaie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bulletinpaie_show', methods: ['GET'])]
    public function show(Bulletinpaie $bulletinpaie): Response
    {
        return $this->render('bulletinpaie/show.html.twig', [
            'bulletinpaie' => $bulletinpaie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bulletinpaie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bulletinpaie $bulletinpaie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BulletinpaieType::class, $bulletinpaie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_bulletinpaie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bulletinpaie/edit.html.twig', [
            'bulletinpaie' => $bulletinpaie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bulletinpaie_delete', methods: ['DELETE', 'POST'])]
    public function delete(Request $request, Bulletinpaie $bulletinpaie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bulletinpaie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($bulletinpaie);
            $entityManager->flush();
            $this->addFlash('success', 'Bulletin supprimé avec succès!');
        } else {
            $this->addFlash('error', 'Token CSRF invalide!');
        }
    
        return $this->redirectToRoute('app_bulletinpaie_index');
    }
}
