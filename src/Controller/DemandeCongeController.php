<?php

namespace App\Controller;

use App\Entity\Demandeconge;
use App\Form\DemandecongeType;
use App\Repository\DemandecongeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/demandeconge')]
final class DemandeCongeController extends AbstractController
{
    #[Route('/', name: 'app_demandeconge_index', methods: ['GET'])]
    public function index(DemandecongeRepository $demandecongeRepository): Response
    {
        return $this->render('demandeconge/index.html.twig', [
            'demandeconges' => $demandecongeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_demande_conge_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $demandeconge = new Demandeconge();

    // Set default statut
    $demandeconge->setStatut('en_attente');

    $form = $this->createForm(DemandecongeType::class, $demandeconge);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $demandeconge->setDateDemande(new \DateTime()); // Optional: set the request date here too

        $entityManager->persist($demandeconge);
        $entityManager->flush();

        return $this->redirectToRoute('app_demande_conge_index');
    }

    return $this->render('demandeconge/new.html.twig', [
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_demandeconge_show', methods: ['GET'])]
    public function show(Demandeconge $demandeconge): Response
    {
        return $this->render('demandeconge/show.html.twig', [
            'demandeconge' => $demandeconge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demandeconge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demandeconge $demandeconge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemandecongeType::class, $demandeconge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_demandeconge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demandeconge/edit.html.twig', [
            'demandeconge' => $demandeconge,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_demandeconge_delete', methods: ['POST'])]
    public function delete(Request $request, Demandeconge $demandeconge, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeconge->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($demandeconge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demandeconge_index', [], Response::HTTP_SEE_OTHER);
    }
}
