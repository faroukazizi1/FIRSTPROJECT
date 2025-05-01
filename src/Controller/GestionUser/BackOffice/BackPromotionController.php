<?php

namespace App\Controller\GestionUser\BackOffice;

use App\Entity\Promotion;
use App\Form\Promotion1Type;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/back/promotion')]
final class BackPromotionController extends AbstractController
{
    #[Route('/home_back/GestionPromotion',name: 'app_back_promotion_index', methods: ['GET'])]
    public function index(PromotionRepository $promotionRepository): Response
    {
        return $this->render('GestionUser/BackOffice/promotion/List.html.twig', [
            'promotions' => $promotionRepository->findAll(),
        ]);
    }

    #[Route('/home_back/GestionPromotion/Ajout', name: 'app_back_promotion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(Promotion1Type::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promotion);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_promotion_index');
        }

        return $this->render('GestionUser/BackOffice/promotion/new.html.twig', [
            'promotion' => $promotion,
            'form' => $form,
        ]);
    }

    #[Route('/home_back/GestionPromotion/{id}', name: 'app_back_promotion_show', methods: ['GET'])]
    public function show(Promotion $promotion): Response
    {
        return $this->render('back_promotion/show.html.twig', [
            'promotion' => $promotion,
        ]);
    }

    #[Route('/home_back/GestionPromotion/{id}/edit', name: 'app_back_promotion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Promotion1Type::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_promotion_index');
        }

        return $this->render('GestionUser/BackOffice/promotion/edit.html.twig', [
            'promotion' => $promotion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_promotion_delete', methods: ['POST'])]
    public function delete(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promotion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($promotion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_promotion_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
