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
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/GestionPromotion')]
#[IsGranted('IS_AUTHENTICATED_FULLY')] // This will ensure the entire controller requires authentication
final class PromotionController extends AbstractController
{
    #[Route('/', name: 'app_promotion_index', methods: ['GET'])]
    public function List(PromotionRepository $promotionRepository): Response
    {
        // Additional security check if needed
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        
        try {
            return $this->render('GestionUser/FrontOffice/promotion/List.html.twig', [
                'promotions' => $promotionRepository->findPromotionById($user->getUserIdentifier()),
            ]);
        } catch (\Exception $e) {
            // Handle any errors that might occur
            $this->addFlash('error', 'An error occurred while fetching promotions.');
            return $this->redirectToRoute('app_login');
        }
    }
}