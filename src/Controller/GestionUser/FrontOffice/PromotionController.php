<?php

namespace App\Controller\GestionUser\FrontOffice;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class PromotionController extends AbstractController
{
    #[Route('/GestionPromotion',name: 'app_promotion_index', methods: ['GET'])]
    public function List(PromotionRepository $promotionRepository): Response
    {
        return $this->render('GestionUser\FrontOffice\promotion\List.html.twig', [
            'promotions' => $promotionRepository->findAll(),
        ]);
    }
}
